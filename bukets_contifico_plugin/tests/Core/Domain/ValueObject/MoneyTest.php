<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Exception\InvalidMoneyException;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function testFromInt(): void
    {
        $money = Money::fromInt(1500);
        $this->assertSame(1500, $money->asCents());
        $this->assertSame(15.0, $money->asFloat());
    }

    public function testFromFloat(): void
    {
        $money = Money::fromFloat(15.50);
        $this->assertSame(1550, $money->asCents());
        $this->assertSame(15.50, $money->asFloat());
    }

    public function testZero(): void
    {
        $money = Money::zero();
        $this->assertSame(0, $money->asCents());
        $this->assertSame(0.0, $money->asFloat());
    }

    public function testAdd(): void
    {
        $a = Money::fromFloat(10.50);
        $b = Money::fromFloat(5.25);
        $result = $a->add($b);
        $this->assertSame(15.75, $result->asFloat());
    }

    public function testSubtract(): void
    {
        $a = Money::fromFloat(20.0);
        $b = Money::fromFloat(5.50);
        $result = $a->subtract($b);
        $this->assertSame(14.50, $result->asFloat());
    }

    public function testMultiply(): void
    {
        $money = Money::fromFloat(10.0);
        $result = $money->multiply(3);
        $this->assertSame(30.0, $result->asFloat());
    }

    public function testMultiplyByDecimal(): void
    {
        $money = Money::fromFloat(10.0);
        $result = $money->multiply(0.15);
        $this->assertSame(1.50, $result->asFloat());
    }

    public function testEquals(): void
    {
        $a = Money::fromFloat(25.0);
        $b = Money::fromFloat(25.0);
        $c = Money::fromFloat(30.0);
        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function testGreaterThan(): void
    {
        $a = Money::fromFloat(100.0);
        $b = Money::fromFloat(50.0);
        $this->assertTrue($a->greaterThan($b));
        $this->assertFalse($b->greaterThan($a));
    }

    public function testNegativeMoneyThrowsException(): void
    {
        $this->expectException(InvalidMoneyException::class);
        Money::fromFloat(-10.0);
    }

    public function testFormat(): void
    {
        $money = Money::fromFloat(1234.56);
        $this->assertSame('1,234.56', $money->format());
    }

    public function testChainOperations(): void
    {
        $result = Money::zero()
            ->add(Money::fromFloat(100.0))
            ->subtract(Money::fromFloat(30.0))
            ->multiply(2);
        $this->assertSame(140.0, $result->asFloat());
    }
}
