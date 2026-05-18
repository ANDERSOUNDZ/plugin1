<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\ProductRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;

final class InventorySyncService
{
    private ContificoClientPort $api;
    private ProductRepositoryPort $productRepo;
    private LoggerPort $logger;
    private SettingsPort $settings;

    public function __construct(
        ContificoClientPort $api,
        ProductRepositoryPort $productRepo,
        LoggerPort $logger,
        SettingsPort $settings
    ) {
        $this->api         = $api;
        $this->productRepo = $productRepo;
        $this->logger      = $logger;
        $this->settings    = $settings;
    }

    public function syncStockFromContifico(): array
    {
        $result      = array('synced' => 0, 'updated' => 0, 'errors' => array());
        $warehouseId = (string) $this->settings->get('bodega_id', '');
        if (empty($warehouseId)) {
            $this->logger->warning('No warehouse configured for stock sync');
            $result['errors'][] = 'No warehouse configured';
            return $result;
        }

        $page = 1;
        while (true) {
            $data = $this->api->getProducts($page, 100);
            if (isset($data['error'])) {
                $result['errors'][] = 'API error on page ' . $page;
                break;
            }
            $items = $data['results'] ?? array();
            if (empty($items)) {
                break;
            }
            foreach ($items as $prod) {
                $this->syncProductStock($prod, $warehouseId, $result);
            }
            $page++;
            $total = (int) ($data['count'] ?? 0);
            if ($page * 100 >= $total) {
                break;
            }
        }

        $this->logger->info('Stock sync from Contifico completed', $result);
        return $result;
    }

    private function syncProductStock(array $prod, string $warehouseId, array &$result): void
    {
        $sku = (string) ($prod['codigo'] ?? '');
        if (empty($sku)) {
            return;
        }
        $productoId = (string) ($prod['id'] ?? '');
        $stockData  = $this->api->getProductStock($productoId);
        if (empty($stockData)) {
            return;
        }
        $stockEnBodega = 0.0;
        foreach ($stockData as $entry) {
            $entryBodegaId = (string) ($entry['bodega_id'] ?? $entry['id_bodega'] ?? '');
            if ($entryBodegaId === $warehouseId) {
                $stockEnBodega = (float) ($entry['cantidad'] ?? $entry['stock'] ?? 0);
                break;
            }
        }
        $wcProductId = wc_get_product_id_by_sku($sku);
        if (!$wcProductId) {
            return;
        }
        $wcProduct = wc_get_product($wcProductId);
        if (!$wcProduct) {
            return;
        }
        $currentStock = (float) $wcProduct->get_stock_quantity();
        if (abs($stockEnBodega - $currentStock) > 0.001) {
            $this->productRepo->updateStock((int) $wcProductId, $stockEnBodega);
            $result['updated']++;
        }
        $result['synced']++;
    }
}
