<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Entity;

final class Warehouse
{
    private string $id;
    private string $nombre;
    private string $codigo;
    private bool $activo;

    public function __construct(
        string $id,
        string $nombre,
        string $codigo = '',
        bool $activo = true,
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->codigo = $codigo;
        $this->activo = $activo;
    }

    public function id(): string { return $this->id; }
    public function nombre(): string { return $this->nombre; }
    public function codigo(): string { return $this->codigo; }
    public function activo(): bool { return $this->activo; }
}
