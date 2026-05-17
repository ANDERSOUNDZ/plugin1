# GESTOR DE SPRINTS — Planificación y seguimiento
> El agente usa esta guía para planificar sprints, hacer seguimiento diario
> y generar reportes de avance según la metodología elegida por el usuario.

---

## 🏁 Configuración inicial

Cuando el usuario inicia un proyecto por primera vez:

```
Pregunta al usuario:
"Antes de empezar, necesito saber cómo organizas tu trabajo.
 ¿Qué metodología usas? Puedo recomendarte si no estás seguro.

 1. Scrum — Sprints fijos de 1-4 semanas (recomendado para equipos)
 2. Kanban — Flujo continuo sin sprints (recomendado para proyectos pequeños)
 3. Scrumban — Híbrido, lo mejor de ambos
 4. No sé — Hazme 3 preguntas y te recomiendo"

Cuando el usuario elija:
1. Guardar en project-context.md la metodología
2. Generar la documentación inicial correspondiente
3. Si eligió "No sé", ejecutar el cuestionario de sprint-methodology-guide.md
```

---

## 📋 Según la metodología elegida

### Si el usuario elige SCRUM

```
Cada sprint (cada 1-4 semanas):

INICIO DEL SPRINT:
1. Preguntar: "¿Cuál es el objetivo de este sprint?"
2. Revisar user stories pendientes del backlog
3. Seleccionar historias para el sprint (prioridad Must/Should)
4. Estimar capacidad del equipo
5. Generar docs/sprints/sprint-plan-[N].md
6. Crear GitHub Milestone: "Sprint N"
7. Asignar issues al milestone

DURANTE EL SPRINT:
1. En cada interacción, preguntar avance si pasaron +24h
2. Actualizar sprint-backlog-[N].md
3. Registrar bloqueos

FIN DEL SPRINT:
1. Generar sprint-review-[N].md
2. Preguntar: "¿Demo para el cliente?"
3. Ejecutar retrospectiva
4. Cerrar milestone en GitHub
5. Preparar sprint planning del siguiente
```

**Documentos que se generan automáticamente:**
- `docs/sprints/sprint-plan-[N].md` — Planificación
- `docs/sprints/sprint-backlog-[N].md` — Backlog + daily tracking
- `docs/sprints/sprint-review-[N].md` — Review + Retro

### Si el usuario elige KANBAN

```
FLUJO CONTINUO:

CADA SEMANA (o cuando el usuario lo pida):
1. Revisar estado del Kanban board
2. Generar status-report-[fecha].md
3. Identificar cuellos de botella (WIP limits)
4. Sugerir priorización del backlog

CADA HITO / RELEASE:
1. Generar milestone-report.md
2. Preparar documentación de entrega
```

**Documentos que se generan automáticamente:**
- `docs/progress/status-report-[fecha].md` — Reporte semanal
- `docs/progress/milestone-report.md` — Reporte por hito

### Si el usuario elige SCRUMBAN

```
SPRINT LIGERO (similar a Scrum pero con menos estructura):

INICIO:
1. Definir objetivo del sprint
2. Seleccionar historias (sin estimaciones detalladas)
3. Definir WIP limits para el sprint
4. Generar sprint-plan-[N].md (versión simplificada)

DURANTE:
1. Seguimiento con Kanban board
2. Daily standup opcional

FIN:
1. Sprint review
2. Retrospectiva
```

---

## 🤖 Comandos rápidos que el usuario puede usar

El usuario puede decir cualquiera de estas frases y el agente responde generando la documentación:

| El usuario dice... | El agente genera... |
|-------------------|-------------------|
| "Inicia el sprint / Sprint planning" | `sprint-plan-[N].md` + milestone en GitHub |
| "Cierra el sprint / Sprint review" | `sprint-review-[N].md` + cierra milestone |
| "Status / Dame el status" | `status-report-[fecha].md` |
| "Reporte de sesión / Resumen de hoy" | `session-report-[fecha].md` |
| "Reporte de hito / Milestone report" | `milestone-report.md` |
| "Genera todo / Documentación completa" | TODOS los documentos pendientes |

---

## 🔄 Actualización automática

Cada vez que el agente:
- Completa una user story → actualiza el sprint backlog
- Encuentra un bug → lo registra en el sprint actual
- Toma una decisión → la incluye en el próximo status report
- Cierra una fase → genera milestone report automáticamente antes de avanzar
