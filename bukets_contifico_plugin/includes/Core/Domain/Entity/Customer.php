<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\ValueObject\Cedula;
use Bukets\Contifico\Core\Domain\ValueObject\Ruc;

final class Customer
{
    private string $id;
    private string $razonSocial;
    private string $tipo;
    private ?Cedula $cedula;
    private ?Ruc $ruc;
    private string $email;
    private string $telefono;
    private string $direccion;

    public function __construct(
        string $id,
        string $razonSocial,
        string $tipo = 'N',
        ?Cedula $cedula = null,
        ?Ruc $ruc = null,
        string $email = '',
        string $telefono = '',
        string $direccion = '',
    ) {
        $this->id = $id;
        $this->razonSocial = $razonSocial;
        $this->tipo = $tipo;
        $this->cedula = $cedula;
        $this->ruc = $ruc;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
    }

    public function id(): string { return $this->id; }
    public function razonSocial(): string { return $this->razonSocial; }
    public function tipo(): string { return $this->tipo; }
    public function cedula(): ?Cedula { return $this->cedula; }
    public function ruc(): ?Ruc { return $this->ruc; }
    public function email(): string { return $this->email; }
    public function telefono(): string { return $this->telefono; }
    public function direccion(): string { return $this->direccion; }

    public function identificacion(): string
    {
        if ($this->cedula !== null) {
            return $this->cedula->value();
        }
        if ($this->ruc !== null) {
            return $this->ruc->value();
        }
        return '';
    }
}
