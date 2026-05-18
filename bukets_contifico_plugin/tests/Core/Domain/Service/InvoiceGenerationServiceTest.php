<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Service\InvoiceGenerationService;
use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\InvoiceRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Cedula;
use PHPUnit\Framework\TestCase;

final class InvoiceGenerationServiceTest extends TestCase
{
    private ContificoClientPort $api;
    private InvoiceRepositoryPort $invoiceRepo;
    private LoggerPort $logger;
    private SettingsPort $settings;
    private InvoiceGenerationService $service;
    private Customer $customer;

    protected function setUp(): void
    {
        $this->api = $this->createMock(ContificoClientPort::class);
        $this->invoiceRepo = $this->getMockBuilder(InvoiceRepositoryPort::class)
            ->disableOriginalConstructor()
            ->disableAutoReturnValueGeneration()
            ->getMock();
        $this->logger = $this->createMock(LoggerPort::class);
        $this->settings = $this->createMock(SettingsPort::class);

        $this->service = new InvoiceGenerationService(
            $this->api,
            $this->invoiceRepo,
            $this->logger,
            $this->settings,
        );

        $this->customer = new Customer(
            '',
            'Juan Perez',
            'N',
            new Cedula('0922054366'),
        );
    }

    public function testGenerateFromOrderSuccess(): void
    {
        $this->settings->method('get')->willReturnMap([
            ['serie', '001-881'],
            ['iva', 15],
            ['centro_costo_id', ''],
            ['envio_producto_id', ''],
            ['pos_token', 'test-token'],
        ]);

        $this->invoiceRepo->method('getLastDocumentNumber')->willReturn(0);
        $this->invoiceRepo->method('save')->willReturnCallback(function ($invoice) {
            return $invoice;
        });

        $this->api->method('searchPerson')->willReturn($this->customer);
        $this->api->method('createDocument')->willReturn([
            'id' => 'doc001',
            'documento' => '001-881-000000001',
        ]);
        $this->api->method('registerPayment')->willReturn(true);

        $this->logger->expects($this->once())
            ->method('info')
            ->with('Invoice created successfully');

        $result = $this->service->generateFromOrder(
            1,
            $this->customer,
            [
                [
                    'sku' => 'BUK001',
                    'contifico_product_id' => 'p001',
                    'nombre' => 'Buket Rosas',
                    'cantidad' => 2,
                    'precio' => 25.0,
                ],
            ],
            Money::fromFloat(5.0),
            'bacs',
        );

        $this->assertNotNull($result);
        $this->assertSame('doc001', $result->id());
        $this->assertSame(50.0, $result->total()->asFloat());
    }

    public function testGenerateFromOrderWithoutShipping(): void
    {
        $this->settings->method('get')->willReturnMap([
            ['serie', '001-881'],
            ['iva', 15],
            ['centro_costo_id', ''],
            ['envio_producto_id', ''],
            ['pos_token', 'test-token'],
        ]);

        $this->invoiceRepo->method('getLastDocumentNumber')->willReturn(5);
        $this->invoiceRepo->method('save')->willReturnCallback(function ($invoice) {
            return $invoice;
        });
        $this->api->method('searchPerson')->willReturn($this->customer);
        $this->api->method('createDocument')->willReturn([
            'id' => 'doc002',
            'documento' => '001-881-000000006',
        ]);
        $this->api->method('registerPayment')->willReturn(true);

        $result = $this->service->generateFromOrder(
            2,
            $this->customer,
            [
                [
                    'sku' => 'BUK001',
                    'contifico_product_id' => 'p001',
                    'nombre' => 'Buket Rosas',
                    'cantidad' => 1,
                    'precio' => 100.0,
                ],
            ],
            Money::zero(),
            'bacs',
        );

        $this->assertNotNull($result);
        $this->assertSame(100.0, $result->total()->asFloat());
    }

    public function testGenerateFromOrderApiFails(): void
    {
        $this->settings->method('get')->willReturnMap([
            ['serie', '001-881'],
            ['iva', 15],
            ['centro_costo_id', ''],
            ['envio_producto_id', ''],
            ['pos_token', 'test-token'],
        ]);

        $this->invoiceRepo->method('getLastDocumentNumber')->willReturn(0);
        $this->api->method('searchPerson')->willReturn($this->customer);
        $this->api->method('createDocument')->willReturn(null);

        $result = $this->service->generateFromOrder(
            3,
            $this->customer,
            [
                [
                    'sku' => 'BUK001',
                    'contifico_product_id' => 'p001',
                    'nombre' => 'Buket Rosas',
                    'cantidad' => 1,
                    'precio' => 100.0,
                ],
            ],
            Money::zero(),
            'bacs',
        );

        $this->assertNull($result);
    }

    public function testGenerateFromOrderWithoutItems(): void
    {
        $result = $this->service->generateFromOrder(
            4,
            $this->customer,
            [],
            Money::zero(),
            'bacs',
        );

        $this->assertNull($result);
    }
}
