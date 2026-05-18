# ADR #1 — Estructura del Plugin: WordPress Handbook + Hexagonal Architecture

**Fecha:** 2026-05-17
**Estado:** Aceptada

---

## Contexto

Debemos construir un plugin que cumpla estrictamente con el WordPress Plugin Handbook pero que a la vez soporte una arquitectura hexagonal donde el Core de dominio sea 100% independiente de WordPress. El proyecto anterior tenia el Core en `src/` con assets mezclados, lo que no seguia el handbook.

## Decision

Usar la estructura recomendada por el WP Handbook §Best Practices adaptada para hexagonal:

```
bukets-contifico-sync/
├── bukets-contifico-sync.php    ← Plugin header + boot loader
├── uninstall.php                 ← WP_UNINSTALL_PLUGIN
├── readme.txt
├── LICENSE (GPL-2.0+)
├── languages/                    ← i18n (.pot files)
├── includes/
│   ├── Core/                     ← HEXAGONAL (0% WP, testeable sin WP)
│   │   ├── Domain/{Entity,ValueObject,Port,Service}/
│   │   ├── Exception/
│   │   └── Contifico/{Client,Auth,Services}/
│   ├── Admin/                    ← WP Admin adapters (Settings API, Menus)
│   └── Common/                   ← WP adapters (Repository implementations, Hooks, Cron)
├── admin/{css,js}/              ← Admin assets
├── vendor/                       ← Composer
├── tests/Core/                   ← Unit tests (sin WP)
├── tests/Common/                 ← Integration tests (con WP)
├── bin/build-zip.php             ← Build script
├── .github/workflows/{ci.yml,release.yml}
└── composer.json
```

**Principio clave:** El directorio `includes/Core/` NUNCA usa funciones de WP. Las interfaces (Ports) se definen en Core y se implementan en `includes/Common/`.

## Alternativas consideradas

- **Todo en `src/`** (proyecto anterior): No sigue la estructura del WP Handbook. Assets mezclados con codigo.
- **Funciones sueltas en el plugin principal**: No escala para la complejidad del proyecto (55+ archivos).
- **MVC tradicional**: No aísla el dominio de la infraestructura. Dificil de testear unitariamente.

## Consecuencias

### Positivas
- El Core es testeable sin WordPress (tests unitarios rapidos)
- El plugin sigue exactamente la estructura del WP Handbook (§Best Practices)
- Separacion clara de responsabilidades (Core vs Adaptadores)

### Negativas
- Mayor cantidad de archivos (~55 vs ~20 en el proyecto anterior)
- Curva de aprendizaje inicial mas alta

### Riesgos
- Que el Core accidentalmente use funciones de WP (mitigado con PHPStan + code review)

## Revisable cuando
El plugin supere los 100 archivos o cuando PHPStan detecte fugas de WP en el Core.
