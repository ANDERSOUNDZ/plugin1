<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\SettingsPort;

final class CronScheduler
{
    private const CRON_HOOK = 'bukets_contifico_sync_event';
    private SettingsPort $settings;

    public function __construct(SettingsPort $settings)
    {
        $this->settings = $settings;
    }

    public function register(): void
    {
        add_action(self::CRON_HOOK, array($this, 'executeSync'));
        add_filter('cron_schedules', array($this, 'addSchedules'));
    }

    public static function scheduleEvents(): void
    {
        if (!wp_next_scheduled(self::CRON_HOOK)) {
            wp_schedule_event(time(), 'bukets_twice_daily', self::CRON_HOOK);
        }
    }

    public static function clearEvents(): void
    {
        wp_clear_scheduled_hook(self::CRON_HOOK);
    }

    public function addSchedules(array $schedules): array
    {
        $schedules['bukets_twice_daily'] = array(
            'interval' => 12 * HOUR_IN_SECONDS,
            'display'  => __('Twice Daily (12 hours)', 'bukets-contifico'),
        );
        $schedules['bukets_daily'] = array(
            'interval' => DAY_IN_SECONDS,
            'display'  => __('Once Daily (24 hours)', 'bukets-contifico'),
        );
        return $schedules;
    }

    public function executeSync(): void
    {
        $apiKey = $this->settings->get('api_key', '');
        if (empty($apiKey)) {
            return;
        }

        $logger = new Logger();
        $logger->info('Cron sync started');

        try {
            $api    = new ContificoAdapter($this->settings);
            $prodRepo = new ProductRepository();
            $orderRepo = new OrderRepository();

            $productSync = new \Bukets\Contifico\Core\Domain\Service\ProductSyncService($api, $prodRepo, $logger, $this->settings);
            $productSync->syncFromContifico();

            $inventorySync = new \Bukets\Contifico\Core\Domain\Service\InventorySyncService($api, $prodRepo, $logger, $this->settings);
            $inventorySync->syncStockFromContifico();

            $orderSync = new \Bukets\Contifico\Core\Domain\Service\OrderSyncService($api, $orderRepo, $logger, $this->settings);
            $orderSync->syncPendingOrders();

            update_option('bukets_contifico_last_sync', current_time('mysql'));
            $logger->info('Cron sync completed');
        } catch (\Throwable $e) {
            $logger->error('Cron sync error', array('error' => $e->getMessage()));
        }
    }
}
