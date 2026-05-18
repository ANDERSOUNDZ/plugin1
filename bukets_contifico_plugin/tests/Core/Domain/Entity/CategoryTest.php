<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\Entity\Category;
use PHPUnit\Framework\TestCase;

final class CategoryTest extends TestCase
{
    public function testCreateRootCategory(): void
    {
        $category = new Category('cat1', 'Flores');

        $this->assertSame('cat1', $category->id());
        $this->assertSame('Flores', $category->nombre());
        $this->assertNull($category->padreId());
        $this->assertTrue($category->esRaiz());
        $this->assertSame(0, $category->nivel());
        $this->assertTrue($category->activo());
    }

    public function testCreateSubcategory(): void
    {
        $category = new Category(
            'cat2',
            'Rosas',
            'cat1',
            'Flores',
            true,
            1
        );

        $this->assertSame('cat2', $category->id());
        $this->assertSame('Rosas', $category->nombre());
        $this->assertSame('cat1', $category->padreId());
        $this->assertSame('Flores', $category->padreNombre());
        $this->assertFalse($category->esRaiz());
        $this->assertSame(1, $category->nivel());
    }

    public function testInactiveCategory(): void
    {
        $category = new Category('cat3', 'Viejas', null, '', false);

        $this->assertFalse($category->activo());
    }
}
