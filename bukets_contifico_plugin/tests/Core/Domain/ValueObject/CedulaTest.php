<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\ValueObject;

use Bukets\Contifico\Core\Domain\ValueObject\Cedula;
use Bukets\Contifico\Core\Exception\InvalidCedulaException;
use PHPUnit\Framework\TestCase;

final class CedulaTest extends TestCase
{
    public function testValidCedula(): void
    {
        $cedula = new Cedula('0922054366');
        $this->assertSame('0922054366', $cedula->value());
    }

    public function testInvalidShortCedula(): void
    {
        $this->expectException(InvalidCedulaException::class);
        new Cedula('12345');
    }

    public function testInvalidLongCedula(): void
    {
        $this->expectException(InvalidCedulaException::class);
        new Cedula('12345678901');
    }

    public function testInvalidProvince(): void
    {
        $this->expectException(InvalidCedulaException::class);
        new Cedula('3012345678');
    }

    public function testInvalidThirdDigit(): void
    {
        $this->expectException(InvalidCedulaException::class);
        new Cedula('0967890123');
    }

    public function testInvalidVerifierDigit(): void
    {
        $this->expectException(InvalidCedulaException::class);
        new Cedula('0922054367');
    }

    public function testWhitepsaceTrimmed(): void
    {
        $cedula = new Cedula(' 0922054366 ');
        $this->assertSame('0922054366', $cedula->value());
    }

    public function testIsValid(): void
    {
        $this->assertTrue(Cedula::isValid('0922054366'));
        $this->assertFalse(Cedula::isValid('0000000000'));
        $this->assertFalse(Cedula::isValid(''));
    }

    public function testToString(): void
    {
        $cedula = new Cedula('0922054366');
        $this->assertSame('0922054366', (string) $cedula);
    }
}
