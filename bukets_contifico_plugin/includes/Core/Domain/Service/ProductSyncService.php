<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\ProductRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use Bukets\Contifico\Core\Domain\Entity\Product;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;

final class ProductSyncService
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

    public function syncFromContifico(): array
    {
        $result = array('created' => 0, 'updated' => 0, 'errors' => array());
        $page   = 1;

        while (true) {
            $data = $this->api->getProducts($page, 100);
            if (isset($data['error'])) {
                $result['errors'][] = 'API error page ' . $page . ': ' . ($data['error'] ?? 'unknown');
                break;
            }
            $items = $data['results'] ?? array();
            if (empty($items)) {
                break;
            }
            foreach ($items as $prodData) {
                try {
                    $this->syncSingleProduct($prodData, $result);
                } catch (\Throwable $e) {
                    $result['errors'][] = sprintf('Error syncing %s: %s', $prodData['codigo'] ?? '?', $e->getMessage());
                }
            }
            $page++;
            $total = (int) ($data['count'] ?? 0);
            if ($page * 100 >= $total) {
                break;
            }
        }

        $this->logger->info('Product sync completed', $result);
        return $result;
    }

    private function syncSingleProduct(array $prodData, array &$result): void
    {
        $sku = (string) ($prodData['codigo'] ?? '');
        if (empty($sku)) {
            return;
        }
        $product   = $this->mapContificoProductToDomain($prodData);
        $existing  = $this->productRepo->findBySku($sku);
        if ($existing !== null) {
            $wcProductId = $this->productRepo->getWcProductIdBySku($sku);
            if ($wcProductId !== null) {
                $this->productRepo->updateProductData($wcProductId, $product);
                $result['updated']++;
            }
        } else {
            $this->productRepo->findOrCreateBySku($product);
            $result['created']++;
        }
    }

    private function mapContificoProductToDomain(array $data): Product
    {
        return new Product(
            (string) ($data['id'] ?? ''),
            (string) ($data['codigo'] ?? ''),
            (string) ($data['nombre'] ?? ''),
            Money::fromFloat((float) ($data['pvp1'] ?? 0)),
            (float) ($data['cantidad_stock'] ?? 0),
            new Iva((int) ($data['porcentaje_iva'] ?? 15)),
            (string) ($data['categoria_id'] ?? ''),
            (string) ($data['categoria_nombre'] ?? ''),
            (string) ($data['descripcion'] ?? ''),
            (string) ($data['tipo'] ?? 'PRO'),
            !($data['inactivo'] ?? false)
        );
    }
}
