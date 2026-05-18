<?php
declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Plugin Name:       Bukets Contifico Sync
 * Plugin URI:        https://bukets.ec
 * Description:       Sincronización bidireccional de productos, inventario y facturación electrónica con Contifico (Ecuador SRI) para WooCommerce.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Bukets.ec
 * Author URI:        https://bukets.ec
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bukets-contifico
 * Domain Path:       /languages
 * Requires Plugins:  woocommerce
 * Update URI:        https://bukets.ec/plugins/bukets-contifico-sync/
 */

define('BUKETS_CONTIFICO_VERSION', '1.0.0');
define('BUKETS_CONTIFICO_FILE', __FILE__);
define('BUKETS_CONTIFICO_DIR', plugin_dir_path(__FILE__));
define('BUKETS_CONTIFICO_URL', plugin_dir_url(__FILE__));

require_once BUKETS_CONTIFICO_DIR . 'vendor/autoload.php';

register_activation_hook(
    __FILE__,
    array('Bukets\Contifico\Common\PluginKernel', 'activate')
);

register_deactivation_hook(
    __FILE__,
    array('Bukets\Contifico\Common\PluginKernel', 'deactivate')
);

register_uninstall_hook(
    __FILE__,
    'Bukets\Contifico\Common\PluginKernel::uninstall'
);

add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'custom_order_tables',
            __FILE__,
            true
        );
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
            'cart_checkout_blocks',
            __FILE__,
            true
        );
    }
});

add_action('plugins_loaded', array('Bukets\Contifico\Common\PluginKernel', 'boot'));
