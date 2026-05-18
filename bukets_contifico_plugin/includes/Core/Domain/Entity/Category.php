<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Entity;

final class Category
{
    private string $id;
    private string $nombre;
    private ?string $padreId;
    private string $padreNombre;
    private bool $activo;
    private int $nivel;

    public function __construct(
        string $id,
        string $nombre,
        ?string $padreId = null,
        string $padreNombre = '',
        bool $activo = true,
        int $nivel = 0,
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->padreId = $padreId;
        $this->padreNombre = $padreNombre;
        $this->activo = $activo;
        $this->nivel = $nivel;
    }

    public function id(): string { return $this->id; }
    public function nombre(): string { return $this->nombre; }
    public function padreId(): ?string { return $this->padreId; }
    public function padreNombre(): string { return $this->padreNombre; }
    public function activo(): bool { return $this->activo; }
    public function nivel(): int { return $this->nivel; }

    public function esRaiz(): bool
    {
        return $this->padreId === null;
    }
}
