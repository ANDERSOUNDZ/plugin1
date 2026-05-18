<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\OrderRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;

final class OrderSyncService
{
    private ContificoClientPort $api;
    private OrderRepositoryPort $orderRepo;
    private LoggerPort $logger;
    private SettingsPort $settings;

    public function __construct(
        ContificoClientPort $api,
        OrderRepositoryPort $orderRepo,
        LoggerPort $logger,
        SettingsPort $settings
    ) {
        $this->api       = $api;
        $this->orderRepo = $orderRepo;
        $this->logger    = $logger;
        $this->settings  = $settings;
    }

    public function registerInventoryOutput(int $orderId): bool
    {
        $order = $this->orderRepo->findById($orderId);
        if ($order === null) {
            $this->logger->error('Order not found', array('order_id' => $orderId));
            return false;
        }
        if (!$order->isCompleted()) {
            $this->logger->warning('Order not completed, skipping inventory output', array(
                'order_id' => $orderId,
                'status'   => $order->status(),
            ));
            return false;
        }
        if ($this->orderRepo->isSynced($orderId)) {
            $this->logger->info('Order already synced, skipping', array('order_id' => $orderId));
            return true;
        }
        $warehouseId = (string) $this->settings->get('bodega_id', '');
        if (empty($warehouseId)) {
            $this->logger->error('No warehouse configured for inventory output');
            return false;
        }
        foreach ($order->items() as $item) {
            $movementData = array(
                'bodega_id'  => $warehouseId,
                'producto_id' => $item['contifico_product_id'] ?? '',
                'cantidad'   => -abs((float) ($item['cantidad'] ?? 1)),
                'tipo'       => 'EGR',
                'descripcion' => 'Venta WooCommerce - Order #' . $orderId,
            );
            $success = $this->api->createInventoryMovement($movementData);
            if (!$success) {
                $this->logger->error('Failed to register inventory output', array(
                    'order_id'    => $orderId,
                    'product_id'  => $item['contifico_product_id'] ?? '',
                ));
                return false;
            }
        }
        $this->orderRepo->markAsSynced($orderId);
        $this->logger->info('Inventory output registered', array('order_id' => $orderId));
        return true;
    }

    public function syncPendingOrders(): array
    {
        $result = array('synced' => 0, 'failed' => 0, 'errors' => array());
        $since  = new \DateTimeImmutable('-7 days');
        $orders = $this->orderRepo->getCompletedOrdersSince($since);
        foreach ($orders as $order) {
            $success = $this->registerInventoryOutput($order->id());
            if ($success) {
                $result['synced']++;
            } else {
                $result['failed']++;
                $result['errors'][] = 'Order #' . $order->id();
            }
        }
        $this->logger->info('Pending orders sync completed', $result);
        return $result;
    }
}
