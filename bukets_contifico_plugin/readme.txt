=== Bukets Contifico Sync ===
Contributors: bukets
Tags: contifico, factura electronica, SRI, Ecuador, woocommerce, inventario
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin WordPress para sincronización bidireccional de productos, inventario y facturación electrónica con Contifico (Ecuador SRI).

== Description ==

Integra WooCommerce con Contifico para gestión de inventario y facturación electrónica ecuatoriana (SRI).

= Funcionalidades =

* Sincronización bidireccional de productos (Contifico ↔ WooCommerce)
* Actualización automática de stock desde Contifico
* Creación automática de productos en WooCommerce
* Salidas de inventario automáticas al completar pedidos
* Soporte de múltiples bodegas/almacenes
* Sistema de logging detallado
* Interfaz de administración completa
* Sincronización programada (cada 12h/24h)

== Installation ==

1. Upload the `bukets-contifico-sync` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Bukets Contifico > Settings and configure your Contifico API credentials
4. Start syncing!

== Frequently Asked Questions ==

= Qué necesito para usar este plugin? =

Necesitas una cuenta activa en Contifico con API Key (plan Plus o Premium).

= Soporta facturación electrónica ecuatoriana? =

Sí. El plugin genera facturas electrónicas válidas ante el SRI a través de la API de Contifico.

= El plugin afecta al core de WordPress? =

No. El plugin usa su propia estructura de datos y no modifica archivos core. Es 100% independiente.

== Changelog ==

= 1.0.0 =
* Release inicial

== Upgrade Notice ==

= 1.0.0 =
Versión inicial del plugin.
