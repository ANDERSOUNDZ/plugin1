# ADR #3 — Esquema de Base de Datos

**Fecha:** 2026-05-17
**Estado:** Aceptada

---

## Contexto

Necesitamos almacenar facturas electronicas, logs de operaciones y metadata de sincronizacion de ordenes. Segun el WP Handbook §Uninstall Methods, los plugins deben usar tablas propias para datos estructurados y limpiarlos completamente al desinstalarse. No debemos contaminar el core de WordPress ni WooCommerce con datos temporales.

## Decision

Crear 3 tablas personalizadas con prefijo `wp_bukets_contifico_*` usando `dbDelta()` en la activacion y `DROP TABLE` en la desinstalacion:

### Tabla: `bukets_contifico_facturas`
```sql
CREATE TABLE wp_bukets_contifico_facturas (
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
);
```

### Tabla: `bukets_contifico_log`
```sql
CREATE TABLE wp_bukets_contifico_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nivel VARCHAR(10) NOT NULL DEFAULT 'INFO',
    mensaje TEXT NOT NULL,
    contexto LONGTEXT NULL,
    created_at DATETIME NOT NULL,
    KEY idx_nivel (nivel),
    KEY idx_created (created_at)
);
```

### Tabla: `bukets_contifico_order_meta`
```sql
CREATE TABLE wp_bukets_contifico_order_meta (
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
);
```

**Nota:** El prefijo `wp_` es ilustrativo. En codigo usamos `$wpdb->prefix` para respetar el prefijo real.

## Alternativas descartadas

- **postmeta de WooCommerce**: Contamina el core del sistema. Dificil de limpiar en uninstall (requiere buscar y eliminar miles de meta_keys).
- **Options API**: No escala. Almacenar facturas como opciones serializadas es anti-pattern.
- **Custom Post Type**: Overhead innecesario (no necesitamos UI de posts, solo almacenamiento).

## Consecuencias

### Positivas
- Datos aislados del core de WP/WooCommerce
- Limpieza completa en uninstall con DROP TABLE
- Rendimiento predecible (consultas SQL directas con $wpdb)
- Sin riesgo de colision con otros plugins

### Negativas
- Mayor complejidad inicial (creacion de tablas, migraciones)
- No visible desde la UI estandar de WordPress (hay que crear paginas admin propias)

### Riesgos
- Que otro plugin use el mismo nombre de tabla (mitigado con prefijo `bukets_contifico_` de 14+ caracteres)

## Revisable cuando
El volumen de datos requiera particionamiento o archive de facturas antiguas.
