<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Service\OrderSyncService;
use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\OrderRepositoryPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use Bukets\Contifico\Core\Domain\Entity\Order;
use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Money;
use PHPUnit\Framework\TestCase;

final class OrderSyncServiceTest extends TestCase
{
    private ContificoClientPort $api;
    private OrderRepositoryPort $orderRepo;
    private LoggerPort $logger;
    private SettingsPort $settings;
    private OrderSyncService $service;

    protected function setUp(): void
    {
        $this->api = $this->createMock(ContificoClientPort::class);
        $this->orderRepo = $this->createMock(OrderRepositoryPort::class);
        $this->logger = $this->createMock(LoggerPort::class);
        $this->settings = $this->createMock(SettingsPort::class);

        $this->service = new OrderSyncService(
            $this->api,
            $this->orderRepo,
            $this->logger,
            $this->settings,
        );
    }

    public function testRegisterInventoryOutputSuccess(): void
    {
        $order = new Order(
            1,
            'completed',
            new Customer('', 'Test', 'N'),
            Money::fromFloat(100.0),
            Money::zero(),
            'bacs',
            [
                [
                    'sku' => 'BUK001',
                    'contifico_product_id' => 'p001',
                    'nombre' => 'Buket',
                    'cantidad' => 2,
                    'precio' => 50.0,
                ],
            ],
        );

        $this->orderRepo->method('findById')->willReturn($order);
        $this->orderRepo->method('isSynced')->willReturn(false);
        $this->settings->method('get')->with('bodega_id')->willReturn('bodega001');
        $this->api->method('createInventoryMovement')->willReturn(true);

        $this->orderRepo->expects($this->once())->method('markAsSynced');

        $result = $this->service->registerInventoryOutput(1);
        $this->assertTrue($result);
    }

    public function testRegisterInventoryOutputAlreadySynced(): void
    {
        $order = new Order(
            1,
            'completed',
            new Customer('', 'Test', 'N'),
            Money::fromFloat(100.0),
            Money::zero(),
            'bacs',
            [],
        );

        $this->orderRepo->method('findById')->willReturn($order);
        $this->orderRepo->method('isSynced')->willReturn(true);

        $result = $this->service->registerInventoryOutput(1);
        $this->assertTrue($result);
    }

    public function testRegisterInventoryOutputOrderNotCompleted(): void
    {
        $order = new Order(
            1,
            'pending',
            new Customer('', 'Test', 'N'),
            Money::fromFloat(100.0),
            Money::zero(),
            'bacs',
            [],
        );

        $this->orderRepo->method('findById')->willReturn($order);

        $result = $this->service->registerInventoryOutput(1);
        $this->assertFalse($result);
    }

    public function testRegisterInventoryOutputNoWarehouse(): void
    {
        $order = new Order(
            1,
            'completed',
            new Customer('', 'Test', 'N'),
            Money::fromFloat(100.0),
            Money::zero(),
            'bacs',
            [],
        );

        $this->orderRepo->method('findById')->willReturn($order);
        $this->orderRepo->method('isSynced')->willReturn(false);
        $this->settings->method('get')->with('bodega_id')->willReturn('');

        $result = $this->service->registerInventoryOutput(1);
        $this->assertFalse($result);
    }

    public function testRegisterInventoryOutputApiFails(): void
    {
        $order = new Order(
            1,
            'completed',
            new Customer('', 'Test', 'N'),
            Money::fromFloat(100.0),
            Money::zero(),
            'bacs',
            [
                [
                    'sku' => 'BUK001',
                    'contifico_product_id' => 'p001',
                    'nombre' => 'Buket',
                    'cantidad' => 1,
                    'precio' => 50.0,
                ],
            ],
        );

        $this->orderRepo->method('findById')->willReturn($order);
        $this->orderRepo->method('isSynced')->willReturn(false);
        $this->settings->method('get')->with('bodega_id')->willReturn('bodega001');
        $this->api->method('createInventoryMovement')->willReturn(false);

        $result = $this->service->registerInventoryOutput(1);
        $this->assertFalse($result);
    }
}
