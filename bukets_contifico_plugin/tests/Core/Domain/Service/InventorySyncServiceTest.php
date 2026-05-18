<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Service\InventorySyncService;
use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\ProductRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use PHPUnit\Framework\TestCase;

final class InventorySyncServiceTest extends TestCase
{
    private ContificoClientPort $api;
    private ProductRepositoryPort $productRepo;
    private LoggerPort $logger;
    private SettingsPort $settings;
    private InventorySyncService $service;

    protected function setUp(): void
    {
        $this->api         = $this->createMock(ContificoClientPort::class);
        $this->productRepo = $this->createMock(ProductRepositoryPort::class);
        $this->logger      = $this->createMock(LoggerPort::class);
        $this->settings    = $this->createMock(SettingsPort::class);
        $this->service     = new InventorySyncService($this->api, $this->productRepo, $this->logger, $this->settings);
    }

    public function testSyncStockNoWarehouse(): void
    {
        $this->settings->method('get')->with('bodega_id')->willReturn('');

        $result = $this->service->syncStockFromContifico();

        $this->assertSame(0, $result['synced']);
        $this->assertNotEmpty($result['errors']);
    }

    public function testSyncStockWithWarehouse(): void
    {
        $this->settings->method('get')->with('bodega_id')->willReturn('bodega001');

        $this->api->method('getProducts')->willReturnOnConsecutiveCalls(
            [
                'count' => 1,
                'results' => [
                    [
                        'id' => 'p001',
                        'codigo' => 'BUK001',
                        'nombre' => 'Buket',
                        'cantidad_stock' => 100,
                    ],
                ],
            ],
            ['count' => 1, 'results' => []],
        );

        $this->api->method('getProductStock')->willReturn([
            ['bodega_id' => 'bodega001', 'cantidad' => 50],
        ]);

        $this->productRepo->method('findBySku')->willReturn(
            new \Bukets\Contifico\Core\Domain\Entity\Product(
                'p001',
                'BUK001',
                'Buket',
                \Bukets\Contifico\Core\Domain\ValueObject\Money::zero(),
                0.0,
                \Bukets\Contifico\Core\Domain\ValueObject\Iva::quince(),
            )
        );

        $this->productRepo->method('getWcProductIdBySku')->willReturn(100);
        $this->productRepo->method('getCurrentStock')->willReturn(30.0);
        $this->productRepo->method('updateStock')->willReturn(true);

        $result = $this->service->syncStockFromContifico();

        $this->assertSame(1, $result['synced']);
        $this->assertSame(1, $result['updated']);
    }

    public function testSyncStockApiError(): void
    {
        $this->settings->method('get')->with('bodega_id')->willReturn('bodega001');

        $this->api->method('getProducts')->willReturn([
            'error' => 'API failure',
        ]);

        $result = $this->service->syncStockFromContifico();

        $this->assertNotEmpty($result['errors']);
    }
}
