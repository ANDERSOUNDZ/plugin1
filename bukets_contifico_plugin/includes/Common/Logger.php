<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\LoggerPort;

final class Logger implements LoggerPort
{
    private const TABLE_LOG  = 'bukets_contifico_log';
    private const LOG_DIR    = 'bukets-contifico';

    public function info(string $message, array $context = array()): void
    {
        $this->log('INFO', $message, $context);
    }

    public function warning(string $message, array $context = array()): void
    {
        $this->log('WARNING', $message, $context);
    }

    public function error(string $message, array $context = array()): void
    {
        $this->log('ERROR', $message, $context);
        $this->writeToFile('ERROR', $message, $context);
    }

    public function debug(string $message, array $context = array()): void
    {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $this->log('DEBUG', $message, $context);
        }
    }

    private function log(string $level, string $message, array $context = array()): void
    {
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . self::TABLE_LOG,
            array(
                'nivel'      => $level,
                'mensaje'    => $message,
                'contexto'   => !empty($context) ? wp_json_encode($context) : '',
                'created_at' => current_time('mysql'),
            )
        );
    }

    private function writeToFile(string $level, string $message, array $context = array()): void
    {
        $uploadDir = wp_upload_dir();
        $logDir    = $uploadDir['basedir'] . '/' . self::LOG_DIR;
        if (!file_exists($logDir)) {
            wp_mkdir_p($logDir);
        }
        $logFile    = $logDir . '/bukets-contifico-' . date('Y-m-d') . '.log';
        $contextStr = !empty($context) ? ' | ' . wp_json_encode($context) : '';
        $line       = '[' . current_time('mysql') . '] [' . $level . '] ' . $message . $contextStr . PHP_EOL;
        file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    }

    public static function getRecent(int $limit = 50): array
    {
        global $wpdb;
        return $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}" . self::TABLE_LOG . " ORDER BY id DESC LIMIT %d",
                $limit
            ),
            ARRAY_A
        );
    }

    public static function clear(): void
    {
        global $wpdb;
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}" . self::TABLE_LOG);
    }
}
