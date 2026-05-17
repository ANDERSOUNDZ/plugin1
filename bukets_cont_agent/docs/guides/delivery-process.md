# Proceso de Entrega de Documentación

> Cómo el SDLC Agent Framework genera y entrega la documentación del proyecto.

---

## ¿Qué es el proceso de entrega? (para el cliente)

Cuando terminas un proyecto de software, el cliente necesita algo más que código. Necesita entender cómo funciona el sistema, cómo usarlo, cómo desplegarlo y cómo mantenerlo.

Este proceso **genera automáticamente** toda esa documentación mientras desarrollas. No es trabajo extra que haces al final — la documentación se construye sola a medida que avanzas.

**El resultado**: un paquete profesional de documentos listos para entregar al cliente, sin que tengas que sentarte a escribirlos manualmente.

---

## ¿Cómo funciona?

### 1. Generación incremental (fases 1-6)

Durante cada fase del SDLC, el agente genera borradores de los documentos de entrega correspondientes:

```
Fase 1 (Requerimientos)
  ├── Manual de Usuario → secciones: introducción, requisitos del sistema
  └── Glosario de términos

Fase 2 (Diseño)
  ├── Documento de Arquitectura Técnica → stack, modelo de datos, diagramas
  ├── API Reference → contratos de endpoints
  └── Decisiones arquitectónicas → compilación de ADRs

Fase 3 (Desarrollo)
  ├── API Reference → endpoints reales con ejemplos
  └── Manual de Usuario → funcionalidades implementadas

Fase 4 (Testing)
  ├── Todos los documentos → secciones de calidad y cobertura
  └── Guía de Operaciones → runbook basado en bugs encontrados

Fase 5 (Despliegue)
  ├── Guía de Despliegue → pipeline, variables de entorno, rollback
  └── Guía de Operaciones → monitoreo, alertas, backups

Fase 6 (Mantenimiento)
  ├── Release Notes → historial de cambios
  └── Actualizaciones → docs afectados por cambios y parches
```

### 2. Compilación final (Fase 7)

En la Fase 7, el agente:
1. Toma todos los borradores generados incrementalmente
2. Completa las secciones que quedaron pendientes
3. Ejecuta el validador de documentación en cada documento
4. Corrige inconsistencias entre documentos
5. Genera el índice del paquete (README.md)
6. Presenta el paquete completo para aprobación

### 3. Sincronización continua

Cada vez que el proyecto cambia (nueva feature, bug fix, refactor, cambio de configuración), el agente **actualiza automáticamente** los documentos afectados como parte del change-loop.

---

## Documentación de avance y sprints (interna)

Además del paquete de entrega para el cliente, el agente genera documentación de **avance del proyecto** durante el desarrollo:

```
proyecto/
├── docs/
│   ├── delivery/                    ← Paquete para el cliente (Fase 7)
│   │   └── ...
│   ├── sprints/                     ← Documentación de sprints
│   │   ├── sprint-plan-1.md         ← Planificación del Sprint 1
│   │   ├── sprint-backlog-1.md      ← Backlog y daily tracking
│   │   └── sprint-review-1.md       ← Review + Retrospectiva
│   └── progress/                    ← Reportes de avance
│       ├── status-report-2026-05-17.md   ← Reporte semanal
│       ├── session-report-2026-05-17.md  ← Reporte por sesión
│       └── milestone-report-mvp.md       ← Reporte de hito
```

### ¿Qué va en el paquete de entrega y qué es interno?

| Documento | ¿Para el cliente? | Propósito |
|-----------|:----------------:|-----------|
| `delivery/*` | ✅ Sí | Documentación técnica y de usuario final |
| `sprints/*` | ❌ No (opcional) | Seguimiento interno del equipo |
| `progress/*` | ❌ No (opcional) | Reportes de estado para stakeholders |

El paquete `delivery/` es lo que se entrega al cliente. Los sprints y progress son documentación de trabajo interno que puede compartirse opcionalmente.

---

## Ciclo de vida de un documento

```
1. BORRADOR
   ─────────
   Generado durante una fase temprana.
   Puede tener secciones incompletas.
   Almacenado en docs/delivery/ pero marcado como "BORRADOR".
   
2. EN PROGRESO
   ────────────
   El agente actualiza secciones a medida que avanza el proyecto.
   Algunas secciones completas, otras pendientes.
   
3. COMPLETADO
   ──────────
   Todas las secciones tienen contenido basado en fuentes reales.
   Pendiente de validación de calidad.
   
4. VALIDADO
   ─────────
   Pasó el documentation-validator.md.
   Todas las verificaciones de calidad aprobadas.
   
5. APROBADO
   ─────────
   El usuario/cliente revisó y aprobó el documento.
   Listo para entrega final.
```

---

## Integración con el change-loop

Cuando ocurre un cambio en el proyecto:

```
CAMBIO EN EL PROYECTO
         │
         ▼
change-loop (paso 7: DOCUMENTAR)
         │
         ▼
Identificar documentos afectados por el cambio
         │
         ▼
Actualizar secciones correspondientes en cada documento
         │
         ▼
Marcar documentos como "BORRADOR" (necesitan re-validación)
         │
         ▼
Documentos pasan por quality-loop en la siguiente fase
```

---

## Integración con el sync-loop

```
SINCRONIZACIÓN DE DOCUMENTACIÓN AL CIERRE DE SESIÓN
         │
         ├── [ ] Revisar qué cambió en esta sesión
         ├── [ ] Identificar documentos afectados
         ├── [ ] ¿Los docs reflejan el cambio? 
         │       ├── Sí → ✅
         │       └── No → Actualizar docs + marcar como desincronizados
         └── [ ] Registrar estado de documentación en project-context.md
```

---

## Responsabilidades

### El agente (automático)
- Generar borradores de documentación al cierre de cada fase
- Actualizar documentos cuando el proyecto cambia
- Validar calidad de cada documento
- Detectar desincronización entre docs y proyecto
- Compilar paquete completo en Fase 7

### El usuario (revisión)
- Revisar documentos generados por el agente
- Aprobar documentos para entrega
- Señalar si falta información relevante
- Decidir qué documentos incluir en la entrega (según el cliente)

---

## Tipos de entrega según el cliente

| Tipo de cliente | Documentos recomendados |
|----------------|------------------------|
| Técnico (equipo interno del cliente) | Arquitectura, API Reference, Guía de Despliegue, Guía de Operaciones |
| No técnico (usuarios de negocio) | Manual de Usuario, Release Notes, Status Reports |
| Administrativo | Guía de Administración, Seguridad y Compliance, Milestone Reports |
| Completo (todos los stakeholders) | Todos los documentos del paquete + Status Reports |

El agente puede adaptar el paquete según las necesidades específicas del cliente en la Fase 7.
