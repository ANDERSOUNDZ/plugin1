<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Port;

use Bukets\Contifico\Core\Domain\Entity\Product;

interface ProductRepositoryPort
{
    public function findBySku(string $sku): ?Product;
    public function findOrCreateBySku(Product $product): int;
    public function updateStock(int $wcProductId, float $stock): bool;
    public function updateProductData(int $wcProductId, Product $product): bool;
}
