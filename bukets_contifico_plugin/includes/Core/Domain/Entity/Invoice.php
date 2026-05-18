<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\ValueObject\Money;

final class Invoice
{
    private string $id;
    private string $serie;
    private string $documento;
    private \DateTimeImmutable $fechaEmision;
    private string $tipoDocumento;
    private string $tipoRegistro;
    private Customer $cliente;
    private array $detalles;
    private string $descripcion;
    private string $referencia;
    private string $centroCostoId;
    private string $estadoSri;
    private bool $electronico;

    public function __construct(
        string $id,
        string $serie,
        string $documento,
        \DateTimeImmutable $fechaEmision,
        string $tipoDocumento,
        string $tipoRegistro,
        Customer $cliente,
        array $detalles = [],
        string $descripcion = '',
        string $referencia = '',
        string $centroCostoId = '',
        string $estadoSri = '',
        bool $electronico = true,
    ) {
        $this->id = $id;
        $this->serie = $serie;
        $this->documento = $documento;
        $this->fechaEmision = $fechaEmision;
        $this->tipoDocumento = $tipoDocumento;
        $this->tipoRegistro = $tipoRegistro;
        $this->cliente = $cliente;
        $this->detalles = $detalles;
        $this->descripcion = $descripcion;
        $this->referencia = $referencia;
        $this->centroCostoId = $centroCostoId;
        $this->estadoSri = $estadoSri;
        $this->electronico = $electronico;
    }

    public function id(): string { return $this->id; }
    public function serie(): string { return $this->serie; }
    public function documento(): string { return $this->documento; }
    public function fechaEmision(): \DateTimeImmutable { return $this->fechaEmision; }
    public function tipoDocumento(): string { return $this->tipoDocumento; }
    public function tipoRegistro(): string { return $this->tipoRegistro; }
    public function cliente(): Customer { return $this->cliente; }
    public function detalles(): array { return $this->detalles; }
    public function descripcion(): string { return $this->descripcion; }
    public function referencia(): string { return $this->referencia; }
    public function centroCostoId(): string { return $this->centroCostoId; }
    public function estadoSri(): string { return $this->estadoSri; }
    public function electronico(): bool { return $this->electronico; }

    public function subtotal(): Money
    {
        $total = Money::zero();
        foreach ($this->detalles as $line) {
            $total = $total->add($line->subtotal());
        }
        return $total;
    }

    public function subtotal0(): Money
    {
        $total = Money::zero();
        foreach ($this->detalles as $line) {
            if ($line->iva()->isZero()) {
                $total = $total->add($line->subtotal());
            }
        }
        return $total;
    }

    public function subtotal12(): Money
    {
        $total = Money::zero();
        foreach ($this->detalles as $line) {
            if (!$line->iva()->isZero()) {
                $total = $total->add($line->baseGravable());
            }
        }
        return $total;
    }

    public function iva(): Money
    {
        $total = Money::zero();
        foreach ($this->detalles as $line) {
            $total = $total->add($line->ivaValue());
        }
        return $total;
    }

    public function total(): Money
    {
        return $this->subtotal()->add($this->iva());
    }
}
