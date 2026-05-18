<?php
declare(strict_types=1);

namespace Bukets\Contifico\Admin;

use Bukets\Contifico\Common\ContificoAdapter;
use Bukets\Contifico\Common\SettingsRepository;

final class AjaxHandler
{
    public function register(): void
    {
        add_action('wp_ajax_bukets_test_connection', array($this, 'handleTestConnection'));
    }

    public function handleTestConnection(): void
    {
        check_ajax_referer('bukets_ajax', '_wpnonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('No autorizado.');
        }

        $settings = new SettingsRepository();
        $api      = new ContificoAdapter($settings);

        $result = $api->testConnection();
        if ($result) {
            wp_send_json_success('Conexion exitosa');
        } else {
            wp_send_json_error('Error de conexion. Verifica tus credenciales.');
        }
    }
}
