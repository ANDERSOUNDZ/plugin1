<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Domain\ValueObject\Ruc;
use Bukets\Contifico\Core\Exception\InvalidRucException;
use PHPUnit\Framework\TestCase;

final class RucTest extends TestCase
{
    public function testValidRucNatural(): void
    {
        $ruc = new Ruc('0922054366001');
        $this->assertSame('0922054366001', $ruc->value());
    }

    public function testInvalidShortRuc(): void
    {
        $this->expectException(InvalidRucException::class);
        new Ruc('1234567890123');
    }

    public function testInvalidEstablishment(): void
    {
        $this->expectException(InvalidRucException::class);
        new Ruc('0922054366000');
    }

    public function testInvalidLength(): void
    {
        $this->expectException(InvalidRucException::class);
        new Ruc('1234567890');
    }

    public function testToString(): void
    {
        $ruc = new Ruc('0922054366001');
        $this->assertSame('0922054366001', (string) $ruc);
    }
}
