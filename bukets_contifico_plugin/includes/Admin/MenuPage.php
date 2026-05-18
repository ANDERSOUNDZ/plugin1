<?php
declare(strict_types=1);

namespace Bukets\Contifico\Admin;

use Bukets\Contifico\Core\Domain\Port\SettingsPort;

final class MenuPage
{
    private SettingsPort $settings;
    private const MENU_SLUG = 'bukets-contifico';

    public function __construct(SettingsPort $settings)
    {
        $this->settings = $settings;
    }

    public function register(): void
    {
        add_action('admin_menu', array($this, 'addMenus'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
    }

    public function addMenus(): void
    {
        add_menu_page(
            __('Bukets Contifico Sync', 'bukets-contifico'),
            __('Bukets Contifico', 'bukets-contifico'),
            'manage_options',
            self::MENU_SLUG,
            array($this, 'renderDashboard'),
            'dashicons-update',
            55
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Dashboard', 'bukets-contifico'),
            __('Dashboard', 'bukets-contifico'),
            'manage_options',
            self::MENU_SLUG,
            array($this, 'renderDashboard')
        );

        add_submenu_page(
            self::MENU_SLUG,
            __('Settings', 'bukets-contifico'),
            __('Settings', 'bukets-contifico'),
            'manage_options',
            'bukets-ct-settings',
            array($this, 'renderSettings')
        );
    }

    public function renderDashboard(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('No autorizado.', 'bukets-contifico'));
        }

        $apiOk = !empty($this->settings->get('api_key', ''));
        $lastSync = get_option('bukets_contifico_last_sync', __('Nunca', 'bukets-contifico'));
        require BUKETS_CONTIFICO_DIR . 'admin/views/dashboard.php';
    }

    public function renderSettings(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('No autorizado.', 'bukets-contifico'));
        }
        require BUKETS_CONTIFICO_DIR . 'admin/views/settings.php';
    }

    public function enqueueAssets(string $hook): void
    {
        if (strpos($hook, 'bukets-contifico') === false && strpos($hook, 'bukets-ct') === false) {
            return;
        }
        wp_enqueue_style(
            'bukets-contifico-admin',
            BUKETS_CONTIFICO_URL . 'admin/css/admin.css',
            array(),
            BUKETS_CONTIFICO_VERSION
        );
        wp_enqueue_script(
            'bukets-contifico-admin',
            BUKETS_CONTIFICO_URL . 'admin/js/admin.js',
            array('jquery'),
            BUKETS_CONTIFICO_VERSION,
            true
        );
        wp_localize_script(
            'bukets-contifico-admin',
            'bukets_ajax',
            array(
                'url'   => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('bukets_ajax'),
            )
        );
    }
}
