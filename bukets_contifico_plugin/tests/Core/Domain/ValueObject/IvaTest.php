<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Domain\ValueObject\Iva;
use Bukets\Contifico\Core\Exception\InvalidIvaException;
use PHPUnit\Framework\TestCase;

final class IvaTest extends TestCase
{
    public function testValidPercentages(): void
    {
        foreach ([0, 5, 12, 14, 15] as $pct) {
            $iva = new Iva($pct);
            $this->assertSame($pct, $iva->percentage());
        }
    }

    public function testInvalidPercentageThrowsException(): void
    {
        $this->expectException(InvalidIvaException::class);
        new Iva(20);
    }

    public function testCero(): void
    {
        $iva = Iva::cero();
        $this->assertTrue($iva->isZero());
        $this->assertSame(0, $iva->percentage());
    }

    public function testDoce(): void
    {
        $iva = Iva::doce();
        $this->assertSame(12, $iva->percentage());
        $this->assertFalse($iva->isZero());
    }

    public function testQuince(): void
    {
        $iva = Iva::quince();
        $this->assertSame(15, $iva->percentage());
        $this->assertSame(0.15, $iva->asDecimal());
    }

    public function testEquals(): void
    {
        $a = new Iva(12);
        $b = new Iva(12);
        $c = new Iva(15);
        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function testIsZero(): void
    {
        $this->assertTrue((new Iva(0))->isZero());
        $this->assertFalse((new Iva(12))->isZero());
    }

    public function testAsDecimal(): void
    {
        $this->assertSame(0.0, (new Iva(0))->asDecimal());
        $this->assertSame(0.05, (new Iva(5))->asDecimal());
        $this->assertSame(0.12, (new Iva(12))->asDecimal());
        $this->assertSame(0.14, (new Iva(14))->asDecimal());
        $this->assertSame(0.15, (new Iva(15))->asDecimal());
    }
}
