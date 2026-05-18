<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\ProductRepositoryPort;
use Bukets\Contifico\Core\Domain\Entity\Product;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;

final class ProductRepository implements ProductRepositoryPort
{
    public function findBySku(string $sku): ?Product
    {
        $wcProductId = wc_get_product_id_by_sku($sku);
        if (!$wcProductId) {
            return null;
        }
        return $this->buildFromWcProduct((int) $wcProductId);
    }

    public function findOrCreateBySku(Product $product): int
    {
        $existing = $this->findBySku($product->codigo());
        if ($existing !== null) {
            $wcProductId = wc_get_product_id_by_sku($product->codigo());
            if ($wcProductId) {
                $this->updateProductData((int) $wcProductId, $product);
                return (int) $wcProductId;
            }
        }
        return $this->createWcProduct($product);
    }

    public function getWcProductIdBySku(string $sku): ?int
    {
        $wcProductId = wc_get_product_id_by_sku($sku);
        return $wcProductId ? (int) $wcProductId : null;
    }

    public function getCurrentStock(int $wcProductId): ?float
    {
        $wcProduct = wc_get_product($wcProductId);
        if (!$wcProduct) {
            return null;
        }
        return (float) $wcProduct->get_stock_quantity();
    }

    public function updateStock(int $wcProductId, float $stock): bool
    {
        $wcProduct = wc_get_product($wcProductId);
        if (!$wcProduct) {
            return false;
        }
        $wcProduct->set_stock_quantity((int) $stock);
        $wcProduct->set_manage_stock(true);
        $wcProduct->set_stock_status($stock > 0 ? 'instock' : 'outofstock');
        $wcProduct->save();
        return true;
    }

    public function updateProductData(int $wcProductId, Product $product): bool
    {
        $wcProduct = wc_get_product($wcProductId);
        if (!$wcProduct) {
            return false;
        }
        $this->applyProductData($wcProduct, $product);
        $wcProduct->save();
        $this->assignCategory($wcProductId, $product);
        return true;
    }

    private function createWcProduct(Product $product): int
    {
        $wcProduct = new \WC_Product_Simple();
        $this->applyProductData($wcProduct, $product);
        $wcProduct->set_catalog_visibility('visible');
        $wcProductId = $wcProduct->save();
        $this->assignCategory($wcProductId, $product);
        return $wcProductId;
    }

    private function applyProductData(\WC_Product $wcProduct, Product $product): void
    {
        $wcProduct->set_name($product->nombre());
        $wcProduct->set_description($product->descripcion());
        $wcProduct->set_sku($product->codigo());
        $wcProduct->set_regular_price((string) $product->precio()->asFloat());
        $wcProduct->set_manage_stock(true);
        $wcProduct->set_stock_quantity((int) $product->stock());
        $wcProduct->set_stock_status($product->stock() > 0 ? 'instock' : 'outofstock');
        $wcProduct->update_meta_data('contifico_product_id', $product->id());
        $wcProduct->update_meta_data('contifico_categoria_id', $product->categoriaId());
        $wcProduct->update_meta_data('contifico_last_sync', time());
    }

    private function assignCategory(int $wcProductId, Product $product): void
    {
        $catId = $product->categoriaId();
        if (empty($catId)) {
            return;
        }
        global $wpdb;
        $termId = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT term_id FROM {$wpdb->prefix}termmeta WHERE meta_key = 'contifico_cat_id' AND meta_value = %s LIMIT 1",
                $catId
            )
        );
        if ($termId) {
            wp_set_object_terms($wcProductId, (int) $termId, 'product_cat', true);
        }
    }

    private function buildFromWcProduct(int $wcProductId): ?Product
    {
        $wcProduct = wc_get_product($wcProductId);
        if (!$wcProduct) {
            return null;
        }
        return new Product(
            (string) $wcProduct->get_meta('contifico_product_id'),
            (string) $wcProduct->get_sku(),
            (string) $wcProduct->get_name(),
            Money::fromFloat((float) $wcProduct->get_regular_price()),
            (float) $wcProduct->get_stock_quantity(),
            new Iva(15),
            (string) $wcProduct->get_meta('contifico_categoria_id'),
            '',
            (string) $wcProduct->get_description()
        );
    }
}
