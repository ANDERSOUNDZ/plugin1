<?php
declare(strict_types=1);

namespace Bukets\Contifico\Tests\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\Entity\Customer;
use Bukets\Contifico\Core\Domain\ValueObject\Cedula;
use PHPUnit\Framework\TestCase;

final class CustomerTest extends TestCase
{
    public function testCreateNaturalPerson(): void
    {
        $customer = new Customer(
            'c001',
            'Juan Perez',
            'N',
            new Cedula('0922054366'),
            null,
            'juan@email.com',
            '0999999999',
            'Av. Siempre Viva 123'
        );

        $this->assertSame('c001', $customer->id());
        $this->assertSame('Juan Perez', $customer->razonSocial());
        $this->assertSame('N', $customer->tipo());
        $this->assertSame('0922054366', $customer->identificacion());
        $this->assertSame('juan@email.com', $customer->email());
        $this->assertSame('0999999999', $customer->telefono());
        $this->assertSame('Av. Siempre Viva 123', $customer->direccion());
    }

    public function testCreateWithoutId(): void
    {
        $customer = new Customer(
            '',
            'Cliente Nuevo',
            'N',
            null,
            null,
            '',
            '',
            ''
        );

        $this->assertSame('Cliente Nuevo', $customer->razonSocial());
        $this->assertSame('', $customer->identificacion());
    }
}
