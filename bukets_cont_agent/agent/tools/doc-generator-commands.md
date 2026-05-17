# COMANDOS RÁPIDOS DE GENERACIÓN DE DOCUMENTACIÓN
> El agente usa esta guía para interpretar las solicitudes del usuario y generar
> la documentación correcta automáticamente.

---

## Cómo funciona

El usuario dice una frase → el agente identifica el comando → genera el documento automáticamente.

No hay comandos mágicos. El agente analiza el contexto actual del proyecto y genera el documento que corresponde usando las plantillas.

---

## Tabla de comandos

| Lo que dice el usuario | Documento a generar | Plantilla |
|------------------------|-------------------|-----------|
| "Inicia el sprint / Sprint planning / Planifica el sprint" | `docs/sprints/sprint-plan-[N].md` | `docs/templates/sprint/sprint-plan-template.md` |
| "Cierra el sprint / Sprint review / Retrospectiva" | `docs/sprints/sprint-review-[N].md` | `docs/templates/sprint/sprint-review-template.md` |
| "Backlog del sprint / Tareas del sprint" | `docs/sprints/sprint-backlog-[N].md` | `docs/templates/sprint/sprint-backlog-template.md` |
| "Status / Dame el status / Reporte semanal" | `docs/progress/status-report-[YYYY-MM-DD].md` | `docs/templates/progress/status-report-template.md` |
| "Reporte de sesión / Resumen de hoy" | `docs/progress/session-report-[YYYY-MM-DD].md` | `docs/templates/progress/session-report-template.md` |
| "Reporte de hito / Milestone report" | `docs/progress/milestone-report-[nombre].md` | `docs/templates/progress/milestone-report-template.md` |
| "Genera todo / Documentación completa" | Todos los docs pendientes | Todas las plantillas aplicables |

---

## Reglas de generación

1. **Siempre leer project-context.md primero** — para saber fase, metodología, estado actual
2. **Usar la metodología elegida** — si el usuario usa Kanban, no generar sprint planning
3. **Numerar sprints correlativamente** — sprint-1, sprint-2, etc.
4. **Fechar cada documento** — con la fecha del día de generación
5. **No regenerar si ya existe** — si el sprint planning ya está creado para el sprint actual, preguntar si quiere regenerarlo
6. **Rellenar con datos reales** — usar user stories, métricas, decisiones del proyecto

---

## Ejemplos de respuestas del agente

### Usuario: "Inicia el sprint"
```
✅ Sprint planning generado

📋 docs/sprints/sprint-plan-1.md
   Sprint Goal: [definido en la conversación]
   Historias: 3 (2 Must + 1 Should)
   Capacidad: 80%
   Duración: 2 semanas (2026-05-18 → 2026-05-29)

📌 Milestone creado en GitHub: "Sprint 1"
   Issues asignados: #1, #2, #3

¿Necesitas ajustar algo antes de empezar?
```

### Usuario: "Dame el status"
```
📊 STATUS REPORT — Proyecto: InventaApp

🟢 Estado general: En camino

Fase actual: 2 — Diseño (60%)
Último sprint: Sprint 1 completado ✅
  - 3/3 historias completadas
  - Velocidad: 8 puntos

Próximo: Sprint 2 — Desarrollo de API (planificado para mañana)

Riesgos:
  - 📈 Railway downtime: establecer plan de backup
  - ➡️ Cobertura de pruebas: 45% (objetivo: 70%)

Documento generado: docs/progress/status-report-2026-05-17.md
```

### Usuario: "Genera todo"
```
📦 GENERACIÓN COMPLETA DE DOCUMENTACIÓN

Revisando estado actual del proyecto...

✅ docs/progress/status-report-2026-05-17.md — Generado
✅ docs/progress/session-report-2026-05-17.md — Generado
⏳ docs/sprints/sprint-plan-2.md — Pendiente (no hay sprint activo)
   ¿Quieres iniciar el Sprint 2 ahora?

📊 Total: 2 documentos generados, 1 pendiente
```
