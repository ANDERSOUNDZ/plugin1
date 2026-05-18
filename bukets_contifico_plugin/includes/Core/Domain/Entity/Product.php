<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Entity;

use Bukets\Contifico\Core\Domain\ValueObject\Money;
use Bukets\Contifico\Core\Domain\ValueObject\Iva;

final class Product
{
    private string $id;
    private string $codigo;
    private string $nombre;
    private string $descripcion;
    private Money $precio;
    private float $stock;
    private Iva $iva;
    private string $categoriaId;
    private string $categoriaNombre;
    private string $tipo;
    private bool $activo;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        string $id,
        string $codigo,
        string $nombre,
        Money $precio,
        float $stock,
        Iva $iva,
        string $categoriaId = '',
        string $categoriaNombre = '',
        string $descripcion = '',
        string $tipo = 'PRO',
        bool $activo = true,
        ?\DateTimeImmutable $createdAt = null,
    ) {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->stock = $stock;
        $this->iva = $iva;
        $this->categoriaId = $categoriaId;
        $this->categoriaNombre = $categoriaNombre;
        $this->descripcion = $descripcion;
        $this->tipo = $tipo;
        $this->activo = $activo;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }

    public function id(): string { return $this->id; }
    public function codigo(): string { return $this->codigo; }
    public function nombre(): string { return $this->nombre; }
    public function descripcion(): string { return $this->descripcion; }
    public function precio(): Money { return $this->precio; }
    public function stock(): float { return $this->stock; }
    public function iva(): Iva { return $this->iva; }
    public function categoriaId(): string { return $this->categoriaId; }
    public function categoriaNombre(): string { return $this->categoriaNombre; }
    public function tipo(): string { return $this->tipo; }
    public function activo(): bool { return $this->activo; }

    public function withStock(float $stock): self
    {
        $clone = clone $this;
        $clone->stock = $stock;
        return $clone;
    }

    public function withPrecio(Money $precio): self
    {
        $clone = clone $this;
        $clone->precio = $precio;
        return $clone;
    }
}
