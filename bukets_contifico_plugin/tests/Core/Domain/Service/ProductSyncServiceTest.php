<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Service\ProductSyncService;
use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\ProductRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use PHPUnit\Framework\TestCase;

final class ProductSyncServiceTest extends TestCase
{
    private ContificoClientPort $api;
    private ProductRepositoryPort $productRepo;
    private LoggerPort $logger;
    private SettingsPort $settings;
    private ProductSyncService $service;

    protected function setUp(): void
    {
        $this->api = $this->createMock(ContificoClientPort::class);
        $this->productRepo = $this->createMock(ProductRepositoryPort::class);
        $this->logger = $this->createMock(LoggerPort::class);
        $this->settings = $this->createMock(SettingsPort::class);

        $this->service = new ProductSyncService(
            $this->api,
            $this->productRepo,
            $this->logger,
            $this->settings,
        );
    }

    public function testSyncFromContificoWithNewProducts(): void
    {
        $this->api->method('getProducts')->willReturnOnConsecutiveCalls(
            [
                'count' => 2,
                'results' => [
                    [
                        'id' => 'p001',
                        'codigo' => 'BUK001',
                        'nombre' => 'Buket Rosas',
                        'pvp1' => '25.00',
                        'cantidad_stock' => 50,
                        'porcentaje_iva' => 15,
                        'categoria_id' => 'cat001',
                        'tipo' => 'PRO',
                    ],
                    [
                        'id' => 'p002',
                        'codigo' => 'BUK002',
                        'nombre' => 'Buket Girasoles',
                        'pvp1' => '30.00',
                        'cantidad_stock' => 30,
                        'porcentaje_iva' => 12,
                        'categoria_id' => 'cat002',
                        'tipo' => 'PRO',
                    ],
                ],
            ],
            ['count' => 2, 'results' => []],
        );

        $this->productRepo->method('findBySku')->willReturn(null);
        $this->productRepo->expects($this->exactly(2))
            ->method('findOrCreateBySku');

        $this->logger->expects($this->once())
            ->method('info')
            ->with('Product sync completed');

        $result = $this->service->syncFromContifico();

        $this->assertSame(2, $result['created']);
        $this->assertSame(0, $result['updated']);
        $this->assertEmpty($result['errors']);
    }

    public function testSyncFromContificoWithExistingProducts(): void
    {
        $this->api->method('getProducts')->willReturnOnConsecutiveCalls(
            [
                'count' => 1,
                'results' => [
                    [
                        'id' => 'p001',
                        'codigo' => 'BUK001',
                        'nombre' => 'Buket Rosas Updated',
                        'pvp1' => '30.00',
                        'cantidad_stock' => 40,
                        'porcentaje_iva' => 15,
                        'categoria_id' => 'cat001',
                        'tipo' => 'PRO',
                    ],
                ],
            ],
            ['count' => 1, 'results' => []],
        );

        $existingProduct = new \Bukets\Contifico\Core\Domain\Entity\Product(
            'p001',
            'BUK001',
            'Buket Rosas Updated',
            \Bukets\Contifico\Core\Domain\ValueObject\Money::fromFloat(30.0),
            40.0,
            \Bukets\Contifico\Core\Domain\ValueObject\Iva::quince(),
        );
        $this->productRepo->method('findBySku')->willReturn($existingProduct);
        $this->productRepo->method('getWcProductIdBySku')->willReturn(100);
        $this->productRepo->expects($this->once())
            ->method('updateProductData');

        $result = $this->service->syncFromContifico();

        $this->assertSame(0, $result['created']);
        $this->assertSame(1, $result['updated']);
    }

    public function testSyncWithApiError(): void
    {
        $this->api->method('getProducts')->willReturn([
            'error' => 'Connection failed',
        ]);

        $result = $this->service->syncFromContifico();

        $this->assertSame(0, $result['created']);
        $this->assertSame(0, $result['updated']);
        $this->assertNotEmpty($result['errors']);
        $this->assertStringContainsString('API error', $result['errors'][0]);
    }

    public function testSyncWithEmptyResults(): void
    {
        $this->api->method('getProducts')->willReturn([
            'count' => 0,
            'results' => [],
        ]);

        $result = $this->service->syncFromContifico();

        $this->assertSame(0, $result['created']);
        $this->assertSame(0, $result['updated']);
        $this->assertEmpty($result['errors']);
    }
}
