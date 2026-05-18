<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\Entity\InvoiceLine;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;
use PHPUnit\Framework\TestCase;

final class InvoiceLineTest extends TestCase
{
    public function testCreateLine(): void
    {
        $line = new InvoiceLine(
            productoId: 'prod123',
            serie: '001-001',
            descripcion: 'Buket de Rosas',
            cantidad: 2,
            precioUnitario: Money::fromFloat(25.0),
            iva: new Iva(15),
        );

        $this->assertSame('prod123', $line->productoId());
        $this->assertSame('001-001', $line->serie());
        $this->assertSame('Buket de Rosas', $line->descripcion());
        $this->assertSame(2.0, $line->cantidad());
        $this->assertSame(25.0, $line->precioUnitario()->asFloat());
        $this->assertSame(15, $line->iva()->percentage());
    }

    public function testSubtotal(): void
    {
        $line = new InvoiceLine(
            productoId: '1',
            serie: '001-001',
            descripcion: 'Test',
            cantidad: 3,
            precioUnitario: Money::fromFloat(10.0),
            iva: new Iva(15),
        );
        $this->assertSame(30.0, $line->subtotal()->asFloat());
    }

    public function testBaseGravableWithIva(): void
    {
        $line = new InvoiceLine(
            productoId: '1',
            serie: '001-001',
            descripcion: 'Test',
            cantidad: 2,
            precioUnitario: Money::fromFloat(100.0),
            iva: new Iva(15),
        );
        $this->assertSame(200.0, $line->baseGravable()->asFloat());
    }

    public function testBaseGravableWithZeroIva(): void
    {
        $line = new InvoiceLine(
            productoId: '1',
            serie: '001-001',
            descripcion: 'Test',
            cantidad: 2,
            precioUnitario: Money::fromFloat(100.0),
            iva: Iva::cero(),
        );
        $this->assertSame(0.0, $line->baseGravable()->asFloat());
    }

    public function testIvaValue(): void
    {
        $line = new InvoiceLine(
            productoId: '1',
            serie: '001-001',
            descripcion: 'Test',
            cantidad: 2,
            precioUnitario: Money::fromFloat(100.0),
            iva: new Iva(15),
        );
        $this->assertSame(30.0, $line->ivaValue()->asFloat());
    }

    public function testIvaValueZero(): void
    {
        $line = new InvoiceLine(
            productoId: '1',
            serie: '001-001',
            descripcion: 'Test',
            cantidad: 2,
            precioUnitario: Money::fromFloat(100.0),
            iva: Iva::cero(),
        );
        $this->assertSame(0.0, $line->ivaValue()->asFloat());
    }
}
