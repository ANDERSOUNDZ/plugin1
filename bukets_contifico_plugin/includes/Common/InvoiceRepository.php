<?php
declare(strict_types=1);

namespace Bukets\Contifico\Common;

use Bukets\Contifico\Core\Domain\Port\InvoiceRepositoryPort;
use Bukets\Contifico\Core\Domain\Entity\Invoice;
use Bukets\Contifico\Core\Domain\Entity\Customer;

final class InvoiceRepository implements InvoiceRepositoryPort
{
    private const TABLE_FACTURAS = 'bukets_contifico_facturas';
    private const TABLE_LOG      = 'bukets_contifico_log';
    private const TABLE_ORDER_META = 'bukets_contifico_order_meta';

    public function save(Invoice $invoice, int $orderId = 0, array $rawResponse = array()): Invoice
    {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE_FACTURAS;
        $wpdb->replace(
            $table,
            array(
                'order_id'               => $orderId,
                'contifico_documento_id' => $invoice->id(),
                'documento_numero'       => $invoice->documento(),
                'total'                  => $invoice->total()->asFloat(),
                'fecha_emision'          => $invoice->fechaEmision()->format('Y-m-d'),
                'fecha_sync'             => current_time('mysql'),
                'raw_response'           => !empty($rawResponse) ? wp_json_encode($rawResponse) : null,
            )
        );
        return $invoice;
    }

    public function findById(string $id): ?Invoice
    {
        return $this->findInvoice('contifico_documento_id', $id);
    }

    public function findByDocumento(string $documento): ?Invoice
    {
        return $this->findInvoice('documento_numero', $documento);
    }

    public function getLastDocumentNumber(string $serie): int
    {
        global $wpdb;
        $result = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT documento_numero FROM {$wpdb->prefix}" . self::TABLE_FACTURAS . "
                 WHERE documento_numero LIKE %s
                 ORDER BY id DESC LIMIT 1",
                $serie . '-%'
            )
        );
        if ($result === null) {
            return 0;
        }
        $parts = explode('-', (string) $result);
        return (int) end($parts);
    }

    public function updateSriStatus(string $documentId, string $status): bool
    {
        global $wpdb;
        $updated = $wpdb->update(
            $wpdb->prefix . self::TABLE_FACTURAS,
            array('estado_sri' => $status),
            array('contifico_documento_id' => $documentId)
        );
        return $updated !== false;
    }

    private function findInvoice(string $field, string $value): ?Invoice
    {
        global $wpdb;
        $row = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}" . self::TABLE_FACTURAS . " WHERE {$field} = %s LIMIT 1",
                $value
            ),
            ARRAY_A
        );
        if (!$row) {
            return null;
        }
        return new Invoice(
            $row['contifico_documento_id'],
            '',
            $row['documento_numero'],
            new \DateTimeImmutable($row['fecha_emision']),
            'FAC',
            'CLI',
            new Customer('', '', 'N'),
            array(),
            '',
            '',
            '',
            $row['estado_sri'] ?? ''
        );
    }

    public static function createTables(): void
    {
        global $wpdb;
        $charset = $wpdb->get_charset_collate();
        $tables  = array();

        $tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}" . self::TABLE_FACTURAS . " (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            order_id BIGINT UNSIGNED NOT NULL,
            contifico_documento_id VARCHAR(32) NOT NULL,
            documento_numero VARCHAR(17) NOT NULL,
            total DECIMAL(12,2) NOT NULL DEFAULT 0,
            estado_sri VARCHAR(30) NOT NULL DEFAULT '',
            fecha_emision DATE NOT NULL,
            fecha_sync DATETIME NOT NULL,
            raw_response LONGTEXT NULL,
            UNIQUE KEY uk_doc_id (contifico_documento_id),
            KEY idx_order (order_id)
        ) {$charset}";

        $tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}" . self::TABLE_LOG . " (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nivel VARCHAR(10) NOT NULL DEFAULT 'INFO',
            mensaje TEXT NOT NULL,
            contexto LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            KEY idx_nivel (nivel),
            KEY idx_created (created_at)
        ) {$charset}";

        $tables[] = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}" . self::TABLE_ORDER_META . " (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            order_id BIGINT UNSIGNED NOT NULL,
            contifico_documento_id VARCHAR(32) NOT NULL DEFAULT '',
            contifico_documento_numero VARCHAR(17) NOT NULL DEFAULT '',
            contifico_documento_total DECIMAL(12,2) NOT NULL DEFAULT 0,
            estado_sri VARCHAR(30) NOT NULL DEFAULT '',
            checks INT UNSIGNED NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            UNIQUE KEY uk_order (order_id)
        ) {$charset}";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        foreach ($tables as $sql) {
            dbDelta($sql);
        }
    }

    public static function dropTables(): void
    {
        global $wpdb;
        foreach (array(self::TABLE_FACTURAS, self::TABLE_LOG, self::TABLE_ORDER_META) as $t) {
            $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$t}");
        }
    }
}
