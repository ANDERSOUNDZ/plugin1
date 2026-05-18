<?php
declare(strict_types=1);

namespace Bukets\Contifico\Admin;

use Bukets\Contifico\Core\Domain\Port\SettingsPort;
use Bukets\Contifico\Common\ContificoAdapter;

final class SettingsPage
{
    private SettingsPort $settings;
    private const OPTION_GROUP = 'bukets_contifico';
    private const SECTION_ID   = 'bukets_contifico_main';

    public function __construct(SettingsPort $settings)
    {
        $this->settings = $settings;
    }

    public function register(): void
    {
        add_action('admin_init', array($this, 'registerSettings'));
    }

    public function registerSettings(): void
    {
        register_setting(
            self::OPTION_GROUP,
            'bukets_contifico_settings',
            array(
                'sanitize_callback' => array($this, 'sanitizeSettings'),
                'default'           => array(),
            )
        );

        add_settings_section(
            self::SECTION_ID,
            __('Conexion Contifico', 'bukets-contifico'),
            array($this, 'renderSection'),
            'bukets-ct-settings'
        );

        add_settings_field(
            'api_key',
            __('API Key', 'bukets-contifico'),
            array($this, 'renderApiKeyField'),
            'bukets-ct-settings',
            self::SECTION_ID
        );

        add_settings_field(
            'pos_token',
            __('POS Token', 'bukets-contifico'),
            array($this, 'renderPosTokenField'),
            'bukets-ct-settings',
            self::SECTION_ID
        );

        add_settings_field(
            'serie',
            __('Serie Documentos', 'bukets-contifico'),
            array($this, 'renderSerieField'),
            'bukets-ct-settings',
            self::SECTION_ID
        );
    }

    public function sanitizeSettings($input): array
    {
        $sanitized = array();
        if (isset($input['api_key'])) {
            $apiKey = sanitize_text_field($input['api_key']);
            if (strpos($apiKey, '****') === false) {
                $sanitized['api_key'] = $apiKey;
            } else {
                $sanitized['api_key'] = $this->settings->get('api_key', '');
            }
        }
        if (isset($input['pos_token'])) {
            $token = sanitize_text_field($input['pos_token']);
            if (strpos($token, '****') === false) {
                $sanitized['pos_token'] = $token;
            } else {
                $sanitized['pos_token'] = $this->settings->get('pos_token', '');
            }
        }
        if (isset($input['serie'])) {
            $sanitized['serie'] = sanitize_text_field($input['serie']);
        }
        return $sanitized;
    }

    public function renderSection(): void
    {
        echo '<p>' . esc_html__('Configura las credenciales de la API de Contifico.', 'bukets-contifico') . '</p>';
    }

    public function renderApiKeyField(): void
    {
        $value = $this->settings->get('api_key', '');
        $display = !empty($value) ? substr($value, 0, 8) . '****' : '';
        ?>
        <input type="text" name="bukets_contifico_settings[api_key]"
               value="<?php echo esc_attr($display); ?>"
               class="regular-text" style="width:400px;" />
        <p class="description"><?php esc_html_e('API Key de Contifico (Plan Plus/Premium)', 'bukets-contifico'); ?></p>
        <?php
    }

    public function renderPosTokenField(): void
    {
        $value = $this->settings->get('pos_token', '');
        $display = !empty($value) ? substr($value, 0, 8) . '****' : '';
        ?>
        <input type="text" name="bukets_contifico_settings[pos_token]"
               value="<?php echo esc_attr($display); ?>"
               class="regular-text" style="width:400px;" />
        <?php
    }

    public function renderSerieField(): void
    {
        $value = $this->settings->get('serie', '001-881');
        ?>
        <input type="text" name="bukets_contifico_settings[serie]"
               value="<?php echo esc_attr($value); ?>"
               class="regular-text" style="width:200px;" />
        <p class="description"><?php esc_html_e('Serie para facturacion electronica', 'bukets-contifico'); ?></p>
        <?php
    }
}
