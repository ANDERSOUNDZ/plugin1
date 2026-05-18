<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\Entity\Warehouse;
use PHPUnit\Framework\TestCase;

final class WarehouseTest extends TestCase
{
    public function testCreateWarehouse(): void
    {
        $warehouse = new Warehouse(
            'w001',
            'Bodega Principal',
            'BOD001',
            true
        );

        $this->assertSame('w001', $warehouse->id());
        $this->assertSame('Bodega Principal', $warehouse->nombre());
        $this->assertSame('BOD001', $warehouse->codigo());
        $this->assertTrue($warehouse->activo());
    }

    public function testInactiveWarehouse(): void
    {
        $warehouse = new Warehouse('w002', 'Inactiva', '', false);
        $this->assertFalse($warehouse->activo());
    }

    public function testDefaultValues(): void
    {
        $warehouse = new Warehouse('w003', 'Minima');
        $this->assertSame('', $warehouse->codigo());
        $this->assertTrue($warehouse->activo());
    }
}
