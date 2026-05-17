# CONFIGURACIÓN DEL FRAMEWORK — Versión para el agente
> Traducción de framework.yml a instrucciones que el agente puede leer y aplicar.
> El agente DEBE leer esta configuración al inicio de cada sesión.

---

## CALIDAD

### Umbrales de calidad

| Parámetro | Valor | Qué significa para ti |
|-----------|-------|----------------------|
| `test_coverage_minimum` | 70% | No aceptes código nuevo si la cobertura de pruebas está por debajo de 70% |
| `critical_bugs_allowed` | 0 | Bloquea cualquier avance si hay bugs críticos abiertos |
| `max_function_lines` | 20 | Alerta si una función supera las 20 líneas |
| `max_nesting_depth` | 3 | Alerta si hay más de 3 niveles de anidamiento |
| `max_function_params` | 4 | Alerta si una función tiene más de 4 parámetros |

### Bucle de calidad

| Configuración | Valor | Acción |
|--------------|-------|--------|
| `blocking` | true | **BLOQUEA** el avance si el bucle de calidad está abierto. No pasar a la siguiente fase hasta que todos los items estén resueltos. |
| `auto_checklist` | true | Muestra el checklist automáticamente al cerrar cada fase |
| `require_adr_for_decisions` | true | Toda decisión importante debe documentarse como ADR |
| `learn_from_errors` | true | Registra cada error en "Errores Aprendidos" automáticamente |
| `require_docs_sync` | true | **BLOQUEA** el avance si los documentos de entrega están desincronizados con el estado actual del proyecto |

---

## FASES ACTIVAS

Todas las fases 0-7 están activas. Ejecútalas en orden secuencial. No omitas ninguna.

---

## DOCUMENTACIÓN DE ENTREGA

| Configuración | Valor | Acción |
|--------------|-------|--------|
| `auto_generate` | true | Genera documentos automáticamente al cerrar cada fase |
| `generate_per_phase` | true | Genera incrementalmente durante todas las fases (no esperar a Fase 7) |
| `validate_before_delivery` | true | Valida calidad de cada documento antes de considerar completa la Fase 7 |
| `require_client_approval` | true | El usuario/cliente debe aprobar el paquete antes de cerrar Fase 7 |
| `output_dir` | docs/delivery | Los documentos generados se guardan en esta carpeta |

### Documentos a generar

Todos están habilitados:
- technical_architecture (Arquitectura Técnica)
- user_manual (Manual de Usuario)
- api_reference (API Reference)
- deployment_guide (Guía de Despliegue)
- operations_guide (Guía de Operaciones)
- release_notes (Release Notes)
- admin_guide (Guía de Administración)
- security_compliance (Seguridad y Compliance)

---

## REVISIÓN DE CÓDIGO

| Configuración | Valor | Acción |
|--------------|-------|--------|
| `require_review_before_merge` | true | No permitir merge sin code review |
| `min_reviewers` | 1 | Al menos 1 revisión antes del merge |
| `block_on_critical` | true | Bloquea merge si hay issues críticos |
| `block_on_high` | true | Bloquea merge si hay issues altos |

---

## COMUNICACIÓN

| Configuración | Valor | Acción |
|--------------|-------|--------|
| `show_reasoning` | true | Explica el razonamiento detrás de cada decisión |
| `proactive_suggestions` | true | Sugiere mejoras aunque no te las pidan |
| `use_checklists` | true | Usa checklists visuales en todas tus respuestas |
| `phase_status_in_responses` | true | **MUESTRA LA FASE ACTUAL al inicio de cada respuesta** usando el formato: `📌 Fase [N] — [nombre] | Bucle: [ABIERTO/CERRADO]` |

---

## MODELO DE TRABAJO

Por defecto: **Scrum**. Pero el usuario puede elegir Kanban, DevOps o Lean Startup. Respeta la elección del usuario.

---

## IDIOMA

Idioma del framework: **es** (español). Todas las interacciones, documentos y checklists deben estar en español.
