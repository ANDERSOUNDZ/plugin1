<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Common\SettingsRepository;
use Bukets\Contifico\Common\InvoiceRepository;
use Bukets\Contifico\Common\ContificoAdapter;
use Bukets\Contifico\Common\Logger;
use Bukets\Contifico\Common\CronScheduler;
use Bukets\Contifico\Common\OrderHooks;
use Bukets\Contifico\Admin\MenuPage;
use Bukets\Contifico\Admin\SettingsPage;
use Bukets\Contifico\Admin\AjaxHandler;

final class PluginKernel
{
    private static ?self $instance = null;
    private bool $booted = false;

    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function activate(): void
    {
        InvoiceRepository::createTables();
        CronScheduler::scheduleEvents();
    }

    public static function deactivate(): void
    {
        CronScheduler::clearEvents();
    }

    public static function uninstall(): void
    {
        InvoiceRepository::dropTables();
        delete_option('bukets_contifico_settings');
    }

    public static function boot(): void
    {
        $kernel = self::instance();
        if ($kernel->booted) {
            return;
        }

        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array(__CLASS__, 'noticeMissingWooCommerce'));
            return;
        }

        $kernel->initComponents();
        $kernel->booted = true;
    }

    private function initComponents(): void
    {
        $settings = new SettingsRepository();

        if (is_admin()) {
            (new MenuPage($settings))->register();
            (new SettingsPage($settings))->register();
            (new AjaxHandler())->register();
        }

        (new OrderHooks($settings))->register();
        (new CronScheduler($settings))->register();
    }

    public static function noticeMissingWooCommerce(): void
    {
        ?>
        <div class="notice notice-error">
            <p><strong>Bukets Contifico Sync</strong> requiere WooCommerce activo.</p>
        </div>
        <?php
    }

    private function __construct() {}
    private function __clone() {}
    public function __wakeup()
    {
        throw new \RuntimeException('Cannot unserialize singleton');
    }
}
