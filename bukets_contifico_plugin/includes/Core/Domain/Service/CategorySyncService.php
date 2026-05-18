<?php
declare(strict_types=1);

namespace Bukets\Contifico\Core\Domain\Service;

use Bukets\Contifico\Core\Domain\Port\ContificoClientPort;
use Bukets\Contifico\Core\Domain\Port\LoggerPort;
use Bukets\Contifico\Core\Domain\Port\SettingsPort;

final class CategorySyncService
{
    private ContificoClientPort $api;
    private LoggerPort $logger;
    private SettingsPort $settings;

    public function __construct(
        ContificoClientPort $api,
        LoggerPort $logger,
        SettingsPort $settings
    ) {
        $this->api      = $api;
        $this->logger   = $logger;
        $this->settings = $settings;
    }

    public function syncAllCategories(): array
    {
        $result = array('created' => 0, 'existing' => 0, 'errors' => array());

        try {
            $categories = $this->api->getCategories();
            if (isset($categories['error'])) {
                $result['errors'][] = 'Failed to fetch categories from Contifico';
                return $result;
            }
            $items = $categories['results'] ?? $categories;
            $tree  = $this->buildCategoryTree($items);
            foreach ($tree as $catData) {
                $this->syncCategory($catData, 0, $result);
            }
        } catch (\Throwable $e) {
            $result['errors'][] = 'Category sync error: ' . $e->getMessage();
        }

        $this->logger->info('Category sync completed', $result);
        return $result;
    }

    private function buildCategoryTree(array $categories): array
    {
        $tree    = array();
        $indexed = array();
        foreach ($categories as $cat) {
            $id = (string) ($cat['id'] ?? '');
            $indexed[$id] = $cat;
        }
        foreach ($indexed as $id => $cat) {
            $padreId = $cat['padre_id'] ?? $cat['categoria_padre_id'] ?? null;
            if (empty($padreId) || !isset($indexed[$padreId])) {
                $tree[] = $cat;
            }
        }
        return $tree;
    }

    private function syncCategory(array $catData, int $parentTermId, array &$result): void
    {
        $catId = (string) ($catData['id'] ?? '');
        $nombre = (string) ($catData['nombre'] ?? '');
        if (empty($catId) || empty($nombre)) {
            return;
        }
        $existingTermId = $this->findCategoryByContificoId($catId);
        if ($existingTermId) {
            $result['existing']++;
        } else {
            $newTerm = wp_insert_term($nombre, 'product_cat', array('parent' => $parentTermId));
            if (is_wp_error($newTerm)) {
                $result['errors'][] = sprintf('Error creating category %s: %s', $nombre, $newTerm->get_error_message());
                return;
            }
            $termId = (int) ($newTerm['term_id'] ?? 0);
            update_term_meta($termId, 'contifico_cat_id', $catId);
            $result['created']++;
            $existingTermId = $termId;
        }
        $subcategories = $catData['subcategorias'] ?? $catData['children'] ?? array();
        foreach ($subcategories as $subcat) {
            $this->syncCategory($subcat, $existingTermId ?: $parentTermId, $result);
        }
    }

    private function findCategoryByContificoId(string $contificoId): ?int
    {
        global $wpdb;
        $termId = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT term_id FROM {$wpdb->prefix}termmeta WHERE meta_key = 'contifico_cat_id' AND meta_value = %s LIMIT 1",
                $contificoId
            )
        );
        return $termId ? (int) $termId : null;
    }
}
