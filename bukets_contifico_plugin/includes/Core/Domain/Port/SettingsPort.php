<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Port;

interface SettingsPort
{
    public function get(string $key, $default = null);
    public function set(string $key, $value): void;
    public function all(): array;
    public function saveAll(array $data): void;
    public function delete(string $key): void;
    public function validate(array $data): array;
}
