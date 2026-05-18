<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;

final class InvoiceLine
{
    private string $productoId;
    private string $serie;
    private string $descripcion;
    private float $cantidad;
    private Money $precioUnitario;
    private Iva $iva;

    public function __construct(
        string $productoId,
        string $serie,
        string $descripcion,
        float $cantidad,
        Money $precioUnitario,
        Iva $iva,
    ) {
        $this->productoId = $productoId;
        $this->serie = $serie;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->iva = $iva;
    }

    public function productoId(): string { return $this->productoId; }
    public function serie(): string { return $this->serie; }
    public function descripcion(): string { return $this->descripcion; }
    public function cantidad(): float { return $this->cantidad; }
    public function precioUnitario(): Money { return $this->precioUnitario; }
    public function iva(): Iva { return $this->iva; }

    public function subtotal(): Money
    {
        return $this->precioUnitario->multiply($this->cantidad);
    }

    public function baseGravable(): Money
    {
        if ($this->iva->isZero()) {
            return Money::zero();
        }
        return $this->subtotal();
    }

    public function ivaValue(): Money
    {
        return $this->baseGravable()->multiply($this->iva->asDecimal());
    }
}
