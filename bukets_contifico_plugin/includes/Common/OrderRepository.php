<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\OrderRepositoryPort;
use Bukets\Contifico\Core\Domain\Entity\Order;
use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Cedula;

final class OrderRepository implements OrderRepositoryPort
{
    public function findById(int $orderId): ?Order
    {
        $wcOrder = wc_get_order($orderId);
        if (!$wcOrder) {
            return null;
        }
        return $this->buildFromWcOrder($wcOrder);
    }

    public function getCompletedOrdersSince(\DateTimeImmutable $since): array
    {
        $orders = wc_get_orders(array(
            'status'       => array('wc-completed', 'wc-processing'),
            'date_created' => '>=' . $since->format('Y-m-d'),
            'limit'        => -1,
        ));
        $domainOrders = array();
        foreach ($orders as $wcOrder) {
            $domainOrders[] = $this->buildFromWcOrder($wcOrder);
        }
        return $domainOrders;
    }

    public function markAsSynced(int $orderId): bool
    {
        $wcOrder = wc_get_order($orderId);
        if (!$wcOrder) {
            return false;
        }
        $wcOrder->update_meta_data('contifico_synced', true);
        $wcOrder->update_meta_data('contifico_synced_at', current_time('mysql'));
        $wcOrder->save();
        return true;
    }

    public function isSynced(int $orderId): bool
    {
        $wcOrder = wc_get_order($orderId);
        if (!$wcOrder) {
            return false;
        }
        return (bool) $wcOrder->get_meta('contifico_synced', false);
    }

    public static function customerFromWcOrder(\WC_Order $wcOrder): Customer
    {
        $cedula = (string) $wcOrder->get_meta('_billing_cedula');
        return new Customer(
            '',
            $wcOrder->get_billing_first_name() . ' ' . $wcOrder->get_billing_last_name(),
            strlen($cedula) === 13 ? 'J' : 'N',
            !empty($cedula) ? new Cedula($cedula) : null,
            null,
            $wcOrder->get_billing_email(),
            $wcOrder->get_billing_phone(),
            $wcOrder->get_billing_address_1()
        );
    }

    public static function itemsFromWcOrder(\WC_Order $wcOrder): array
    {
        $items = array();
        foreach ($wcOrder->get_items() as $item) {
            $product = $item->get_product();
            $items[] = array(
                'sku'                  => $product ? $product->get_sku() : '',
                'contifico_product_id' => $product ? $product->get_meta('contifico_product_id') : '',
                'nombre'               => $item->get_name(),
                'cantidad'             => $item->get_quantity(),
                'precio'               => $item->get_total() / max(1, $item->get_quantity()),
            );
        }
        return $items;
    }

    private function buildFromWcOrder(\WC_Order $wcOrder): Order
    {
        $customer = self::customerFromWcOrder($wcOrder);
        $items    = self::itemsFromWcOrder($wcOrder);
        return new Order(
            $wcOrder->get_id(),
            $wcOrder->get_status(),
            $customer,
            Money::fromFloat((float) $wcOrder->get_total()),
            Money::fromFloat((float) $wcOrder->get_shipping_total()),
            $wcOrder->get_payment_method(),
            $items,
            new \DateTimeImmutable($wcOrder->get_date_created() ? $wcOrder->get_date_created()->format('Y-m-d H:i:s') : 'now')
        );
    }
}
