<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\SettingsPort;

final class SettingsRepository implements SettingsPort
{
    private const OPTION_KEY = 'bukets_contifico_settings';
    private array $cache;

    public function __construct()
    {
        $this->cache = get_option(self::OPTION_KEY, array());
        if (!is_array($this->cache)) {
            $this->cache = array();
        }
    }

    public function get(string $key, $default = null)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        return $default;
    }

    public function set(string $key, $value): void
    {
        $this->cache[$key] = $value;
        update_option(self::OPTION_KEY, $this->cache);
    }

    public function all(): array
    {
        return $this->cache;
    }

    public function saveAll(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->cache[$key] = $value;
        }
        update_option(self::OPTION_KEY, $this->cache);
    }

    public function delete(string $key): void
    {
        unset($this->cache[$key]);
        update_option(self::OPTION_KEY, $this->cache);
    }

    public function validate(array $data): array
    {
        $errors = array();
        if (isset($data['api_key']) && empty($data['api_key'])) {
            $errors[] = __('API Key es requerida', 'bukets-contifico');
        }
        return $errors;
    }
}
