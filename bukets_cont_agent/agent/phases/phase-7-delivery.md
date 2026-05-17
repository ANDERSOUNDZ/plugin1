# FASE 7 — DOCUMENTACIÓN DE ENTREGA
> Compila, completa y valida el paquete de documentación para entregar al cliente.

---

## Objetivo

Generar un **paquete de documentación profesional, completo y sincronizado** que el cliente pueda usar para operar, mantener y escalar el sistema.

No es documentación técnica interna — es la **cara visible del proyecto** para el cliente, sus usuarios y su equipo técnico.

---

## Tu rol en esta fase

Actúas como **documentador técnico senior**. No generas documentación genérica — extraes información real del proyecto desde:

- Las decisiones arquitectónicas (ADRs) → Documento de Arquitectura
- Las user stories y criterios de aceptación → Manual de Usuario
- Los contratos de API diseñados en Fase 2 y código en Fase 3 → API Reference
- La configuración de infraestructura y CI/CD → Guía de Despliegue
- El plan de operaciones y monitoreo → Guía de Operaciones
- Los cambios registrados en el proyecto → Release Notes
- Las decisiones de seguridad y compliance → Documentación de Seguridad

---

## Proceso de generación de documentación

### Paso 1 — Inventario de documentación existente

Revisa qué documentación se ha generado incrementalmente durante las fases anteriores:

```
INVENTARIO DE DOCUMENTACIÓN
┌─────────────────────────────────────────────────────────────┐
│ Documento                 │ Estado │ Última actualización   │
├───────────────────────────┼────────┼────────────────────────┤
│ Arquitectura Técnica      │ [%]    │ [fecha]                │
│ Manual de Usuario         │ [%]    │ [fecha]                │
│ API Reference             │ [%]    │ [fecha]                │
│ Guía de Despliegue        │ [%]    │ [fecha]                │
│ Guía de Operaciones       │ [%]    │ [fecha]                │
│ Release Notes             │ [%]    │ [fecha]                │
│ Guía de Administración    │ [%]    │ [fecha]                │
│ Seguridad y Compliance   │ [%]    │ [fecha]                │
└───────────────────────────┴────────┴────────────────────────┘
```

### Paso 2 — Completar secciones faltantes

Para cada documento con completitud < 100%, identifica las secciones vacías y complétalas usando la información del proyecto:

- ¿Falta una sección? → Búscala en ADRs, user stories, código o configuración
- ¿La información está desactualizada? → Actualízala con el estado real del proyecto
- ¿Hay ambigüedad? → Resuélvela contrastando con el código o preguntando al usuario

### Paso 3 — Validar calidad de cada documento

Ejecuta el validador de documentación (`../validators/documentation-validator.md`) en cada documento:

```
VALIDACIÓN — [nombre del documento]
[ ] Completitud: todas las secciones están llenas
[ ] Claridad: es entendible para su audiencia objetivo
[ ] Consistencia: términos y formato uniformes en todo el documento
[ ] Actualización: refleja el estado actual del proyecto
[ ] Trazabilidad: cada afirmación se conecta con una fuente real
[ ] Sin placeholder: no hay [completar] o [pendiente] sin resolver
```

### Paso 4 — Compilar paquete de entrega

Una vez que todos los documentos pasan validación, compila el paquete:

```
PAQUETE DE ENTREGA — [nombre del proyecto] v[versión]
┌─────────────────────────────────────────────────────────────┐
│ docs/delivery/                                              │
│ ├── 01-technical-architecture.md   → Arquitectura del sistema│
│ ├── 02-user-manual.md              → Manual de usuario final │
│ ├── 03-api-reference.md            → Referencia de API       │
│ ├── 04-deployment-guide.md         → Guía de despliegue      │
│ ├── 05-operations-guide.md         → Guía de operaciones     │
│ ├── 06-release-notes.md            → Notas de versión        │
│ ├── 07-admin-guide.md              → Guía de administración  │
│ ├── 08-security-compliance.md      → Seguridad y compliance  │
│ └── README.md                      → Índice del paquete      │
└─────────────────────────────────────────────────────────────┘
```

### Paso 5 — Generar índice del paquete (README.md)

Crea un archivo README.md en `docs/delivery/` que sirva como puerta de entrada:

```
# [Nombre del Proyecto] — Documentación de Entrega

> Versión: [versión] | Fecha: [fecha]

## Contenido del paquete

| Documento | Audiencia | Descripción |
|-----------|-----------|-------------|
| [01] Arquitectura Técnica | Equipo técnico del cliente | ... |
| [02] Manual de Usuario | Usuarios finales | ... |
| ... | ... | ... |

## Estado de la documentación

Todos los documentos han sido validados y aprobados.
```

### Paso 6 — Cierre y aprobación

Presenta el resumen final al usuario para aprobación:

```
📦 PAQUETE DE DOCUMENTACIÓN LISTO PARA ENTREGA
┌─────────────────────────────────────────────────────────────┐
│ Documentos generados:      8/8                              │
│ Validación de calidad:     ✅ 100% aprobado                 │
│ Última sincronización:     [fecha]                          │
│                                                             │
│ El paquete completo está en: docs/delivery/                 │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔁 Bucle de calidad — Fase 7

```
CHECKLIST DE CIERRE — DOCUMENTACIÓN DE ENTREGA
[ ] Documento de Arquitectura Técnica generado y validado
[ ] Manual de Usuario generado y validado
[ ] API Reference generada y validada
[ ] Guía de Despliegue generada y validada
[ ] Guía de Operaciones generada y validada
[ ] Release Notes generadas y validadas
[ ] Guía de Administración generada y validada
[ ] Documentación de Seguridad y Compliance generada y validada
[ ] Todos los documentos pasaron el validador de documentación
[ ] No hay placeholders ni secciones vacías en ningún documento
[ ] Cada documento tiene trazabilidad con su fuente (ADR, user story, código)
[ ] El índice del paquete (README.md) está generado
[ ] El usuario/cliente aprobó el paquete de documentación

Estado: [ ] ABIERTO  [ ] CERRADO
```

---

## Documentos que se generan en esta fase

| Documento | Fuente de información | Audiencia |
|-----------|----------------------|-----------|
| Arquitectura Técnica | ADRs, diseño de Fase 2, código | Equipo técnico del cliente |
| Manual de Usuario | User stories (Fase 1), UI/UX (Fase 3) | Usuarios finales |
| API Reference | Contratos de API (Fase 2), código (Fase 3) | Desarrolladores del cliente |
| Guía de Despliegue | Configuración de infraestructura (Fase 5) | DevOps / SysAdmin del cliente |
| Guía de Operaciones | Plan de monitoreo y operaciones (Fase 5-6) | Operadores del sistema |
| Release Notes | Historial de cambios del proyecto | Todos los stakeholders |
| Guía de Administración | Configuración de usuarios, roles, backups | Administradores del sistema |
| Seguridad y Compliance | Decisiones de seguridad y reqs no funcionales | Equipo de seguridad / Compliance |

---

## Integración con fases anteriores

La Fase 7 **no parte de cero**. Durante las fases 1 a 6, el agente ya ha estado generando y actualizando borradores de estos documentos:

```
Fase 1 (Reqs)      → Manual de Usuario (secciones: introducción, requisitos del sistema)
Fase 2 (Diseño)    → Documento de Arquitectura + API Reference (borrador)
Fase 3 (Desarrollo) → API Reference (endpoints reales) + actualizaciones al Manual
Fase 4 (QA)        → Secciones de pruebas y calidad en todos los documentos
Fase 5 (Despliegue) → Guía de Despliegue + Guía de Operaciones
Fase 6 (Mantenimiento) → Release Notes + actualizaciones por cambios
Fase 7 (Entrega)   → COMPILA, COMPLETA Y VALIDA TODO
```

---

## 📊 Reporte de calidad — Fase 7

```
📊 REPORTE DE CALIDAD — Documentación de Entrega
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Documentos completados:      [N]/8
Documentos validados:        [N]/8 (objetivo: 8/8)
Placeholders residuales:     [N] (objetivo: 0)
Docs desincronizados:        [N] (objetivo: 0)
ADRs documentados:           [N]
Documentos con observaciones: [lista si hay]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```
