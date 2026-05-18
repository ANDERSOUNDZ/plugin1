<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use Bukets\Contifico\Core\Domain\Service\OrderSyncService;
use Bukets\Contifico\Core\Domain\Service\InvoiceGenerationService;
use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Cedula;
use Bukets\Contifico\Core\Domain\ValueObject\Money;

final class OrderHooks
{
    private SettingsPort $settings;

    public function __construct(SettingsPort $settings)
    {
        $this->settings = $settings;
    }

    public function register(): void
    {
        add_action('woocommerce_order_status_completed', array($this, 'onOrderCompleted'));
        add_action('woocommerce_order_status_processing', array($this, 'onOrderCompleted'));
    }

    public function onOrderCompleted(int $orderId): void
    {
        $apiKey = $this->settings->get('api_key', '');
        if (empty($apiKey)) {
            return;
        }

        try {
            $api   = new ContificoAdapter($this->settings);
            $logger = new Logger();

            $orderSync = new OrderSyncService(
                $api,
                new OrderRepository(),
                $logger,
                $this->settings
            );
            $orderSync->registerInventoryOutput($orderId);

            $this->maybeGenerateInvoice($orderId, $api, $logger);
        } catch (\Throwable $e) {
            $logger->error('Order hooks error', array(
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ));
        }
    }

    private function maybeGenerateInvoice(int $orderId, ContificoAdapter $api, Logger $logger): void
    {
        if (!$this->settings->get('auto_invoice', true)) {
            return;
        }

        $wcOrder = wc_get_order($orderId);
        if (!$wcOrder) {
            return;
        }

        $cedula = (string) $wcOrder->get_meta('_billing_cedula');
        if (empty($cedula)) {
            $logger->warning('No cedula found for order, skipping invoice', array('order_id' => $orderId));
            return;
        }

        $customer = OrderRepository::customerFromWcOrder($wcOrder);
        $items    = OrderRepository::itemsFromWcOrder($wcOrder);

        $shippingTotal = Money::fromFloat((float) $wcOrder->get_shipping_total());
        $paymentMethod = $wcOrder->get_payment_method();

        $invoiceService = new InvoiceGenerationService(
            $api,
            new InvoiceRepository(),
            $logger,
            $this->settings
        );
        $invoiceService->generateFromOrder($orderId, $customer, $items, $shippingTotal, $paymentMethod);
    }
}
