<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Service\CategorySyncService;
use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use PHPUnit\Framework\TestCase;

final class CategorySyncServiceTest extends TestCase
{
    private ContificoClientPort $api;
    private LoggerPort $logger;
    private SettingsPort $settings;
    private CategorySyncService $service;

    protected function setUp(): void
    {
        $this->api      = $this->createMock(ContificoClientPort::class);
        $this->logger   = $this->createMock(LoggerPort::class);
        $this->settings = $this->createMock(SettingsPort::class);
        $this->service  = new CategorySyncService($this->api, $this->logger, $this->settings);
    }

    public function testSyncAllCategoriesSuccess(): void
    {
        $this->api->method('getCategories')->willReturn([
            'results' => [
                ['id' => 'cat1', 'nombre' => 'Flores', 'padre_id' => null],
                ['id' => 'cat2', 'nombre' => 'Rosas', 'padre_id' => 'cat1'],
                ['id' => 'cat3', 'nombre' => 'Girasoles', 'padre_id' => 'cat1'],
            ],
        ]);

        $this->logger->expects($this->once())
            ->method('info')
            ->with('Category sync completed');

        $result = $this->service->syncAllCategories();

        $this->assertArrayHasKey('created', $result);
        $this->assertArrayHasKey('existing', $result);
        $this->assertArrayHasKey('errors', $result);
    }

    public function testSyncAllCategoriesApiError(): void
    {
        $this->api->method('getCategories')->willReturn([
            'error' => 'API failure',
        ]);

        $result = $this->service->syncAllCategories();

        $this->assertNotEmpty($result['errors']);
    }

    public function testSyncAllCategoriesEmpty(): void
    {
        $this->api->method('getCategories')->willReturn([
            'results' => [],
        ]);

        $result = $this->service->syncAllCategories();

        $this->assertSame(0, $result['created']);
        $this->assertSame(0, $result['existing']);
        $this->assertEmpty($result['errors']);
    }
}
