<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\Entity\Invoice;
use Bukets\Contifico\Core\Domain\Entity\InvoiceLine;
use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;
use Bukets\Contifico\Core\Domain\ValueObject\Cedula;
use PHPUnit\Framework\TestCase;

final class InvoiceTest extends TestCase
{
    private Customer $customer;
    private array $lines;
    private array $mixedLines;

    protected function setUp(): void
    {
        $this->customer = new Customer(
            id: 'c001',
            razonSocial: 'Juan Perez',
            tipo: 'N',
            cedula: new Cedula('0922054366'),
        );

        $this->lines = [
            new InvoiceLine('p1', '001-001', 'Producto 1', 2, Money::fromFloat(50.0), new Iva(15)),
        ];

        $this->mixedLines = [
            new InvoiceLine('p1', '001-001', 'Producto IVA', 2, Money::fromFloat(100.0), new Iva(15)),
            new InvoiceLine('p2', '001-001', 'Producto 0%', 3, Money::fromFloat(50.0), Iva::cero()),
        ];
    }

    public function testCreateInvoice(): void
    {
        $invoice = new Invoice(
            id: 'inv001',
            serie: '001-001',
            documento: '001-001-000000001',
            fechaEmision: new \DateTimeImmutable('2026-05-17'),
            tipoDocumento: 'FAC',
            tipoRegistro: 'CLI',
            cliente: $this->customer,
            detalles: $this->lines,
        );

        $this->assertSame('inv001', $invoice->id());
        $this->assertSame('001-001', $invoice->serie());
        $this->assertSame('FAC', $invoice->tipoDocumento());
        $this->assertSame('CLI', $invoice->tipoRegistro());
        $this->assertTrue($invoice->electronico());
    }

    public function testTotalWithIva(): void
    {
        $invoice = new Invoice(
            id: 'inv001',
            serie: '001-001',
            documento: '001-001-000000001',
            fechaEmision: new \DateTimeImmutable('2026-05-17'),
            tipoDocumento: 'FAC',
            tipoRegistro: 'CLI',
            cliente: $this->customer,
            detalles: $this->mixedLines,
        );

        $this->assertSame(200.0, $invoice->subtotal12()->asFloat());
        $this->assertSame(150.0, $invoice->subtotal0()->asFloat());
        $this->assertSame(350.0, $invoice->subtotal()->asFloat());
        $this->assertSame(30.0, $invoice->iva()->asFloat());
        $this->assertSame(380.0, $invoice->total()->asFloat());
    }

    public function testTotalWithOnlyZeroIva(): void
    {
        $invoice = new Invoice(
            id: 'inv002',
            serie: '001-001',
            documento: '001-001-000000002',
            fechaEmision: new \DateTimeImmutable('2026-05-17'),
            tipoDocumento: 'FAC',
            tipoRegistro: 'CLI',
            cliente: $this->customer,
            detalles: [
                new InvoiceLine('p1', '001-001', 'Exento', 5, Money::fromFloat(20.0), Iva::cero()),
            ],
        );

        $this->assertSame(100.0, $invoice->subtotal0()->asFloat());
        $this->assertSame(0.0, $invoice->subtotal12()->asFloat());
        $this->assertSame(100.0, $invoice->subtotal()->asFloat());
        $this->assertSame(0.0, $invoice->iva()->asFloat());
        $this->assertSame(100.0, $invoice->total()->asFloat());
    }

    public function testEmptyInvoice(): void
    {
        $invoice = new Invoice(
            id: '',
            serie: '',
            documento: '',
            fechaEmision: new \DateTimeImmutable(),
            tipoDocumento: 'FAC',
            tipoRegistro: 'CLI',
            cliente: $this->customer,
        );

        $this->assertSame(0.0, $invoice->subtotal()->asFloat());
        $this->assertSame(0.0, $invoice->iva()->asFloat());
        $this->assertSame(0.0, $invoice->total()->asFloat());
    }
}
