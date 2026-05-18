<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\Entity\Product;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;
use PHPUnit\Framework\TestCase;

final class ProductTest extends TestCase
{
    private Product $product;

    protected function setUp(): void
    {
        $this->product = new Product(
            id: 'abc123',
            codigo: 'BUK001',
            nombre: 'Buket de Rosas Premium',
            precio: Money::fromFloat(45.50),
            stock: 100.0,
            iva: new Iva(15),
            categoriaId: 'cat001',
            categoriaNombre: 'Bukets Premium',
            descripcion: 'Hermoso buket de rosas rojas',
            tipo: 'PRO',
            activo: true,
        );
    }

    public function testCreateProduct(): void
    {
        $this->assertSame('abc123', $this->product->id());
        $this->assertSame('BUK001', $this->product->codigo());
        $this->assertSame('Buket de Rosas Premium', $this->product->nombre());
        $this->assertSame(45.50, $this->product->precio()->asFloat());
        $this->assertSame(100.0, $this->product->stock());
        $this->assertSame(15, $this->product->iva()->percentage());
        $this->assertSame('cat001', $this->product->categoriaId());
        $this->assertSame('PRO', $this->product->tipo());
        $this->assertTrue($this->product->activo());
    }

    public function testWithStock(): void
    {
        $updated = $this->product->withStock(50.0);
        $this->assertSame(50.0, $updated->stock());
        $this->assertSame(100.0, $this->product->stock());
    }

    public function testWithPrecio(): void
    {
        $newPrice = Money::fromFloat(55.0);
        $updated = $this->product->withPrecio($newPrice);
        $this->assertSame(55.0, $updated->precio()->asFloat());
        $this->assertSame(45.50, $this->product->precio()->asFloat());
    }

    public function testDefaultValues(): void
    {
        $product = new Product(
            id: '1',
            codigo: 'TEST',
            nombre: 'Test',
            precio: Money::zero(),
            stock: 0.0,
            iva: Iva::cero(),
        );
        $this->assertSame('', $product->categoriaId());
        $this->assertSame('', $product->descripcion());
        $this->assertSame('PRO', $product->tipo());
        $this->assertTrue($product->activo());
    }
}
