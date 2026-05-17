# Guía de Metodologías de Trabajo

> El agente usa esta guía para recomendar la metodología adecuada al usuario
> y generar la documentación de sprints y avance correspondiente.

---

## ¿Por qué necesitas una metodología?

Una metodología de trabajo define **cómo organizas tu tiempo, tu equipo y tus tareas** para entregar software de forma predecible y con calidad. No es burocracia — es la diferencia entre proyectos que avanzan y proyectos que se estancan.

---

## Las 3 metodologías que el agente puede gestionar

### 1. Scrum (recomendado para equipos)

**¿Qué es?** Trabajas en ciclos fijos llamados **sprints** (1-4 semanas). Al inicio planificas qué harás, al final revisas qué lograste.

**Ideal para:** Equipos de 2+ personas, proyectos con fecha de entrega definida.

**Documentación que genera el agente:**
- Sprint Planning (cada sprint)
- Sprint Review + Retrospective (cada sprint)
- Sprint Backlog con daily tracking
- Status reports semanales

**Ejemplo de ritmo:**
```
Lunes    → Sprint Planning (2h)
Lun-Vie  → Daily standup (15min)
Viernes  → Sprint Review (1h) + Retro (1h)
```

---

### 2. Kanban (recomendado para flujo continuo)

**¿Qué es?** No hay sprints fijos. Tomas tareas del backlog cuando tienes capacidad y las mueves por columnas (Pendiente → En progreso → Hecho). Limitas el trabajo en progreso (WIP).

**Ideal para:** Proyectos con requerimientos cambiantes, equipos pequeños (1-3 personas), mantenimiento continuo.

**Documentación que genera el agente:**
- Kanban board (columnas: Backlog, To Do, In Progress, Review, Done)
- Status reports (sin fechas de sprint)
- Cycle time tracking
- Milestone reports

**Ejemplo de WIP limits:**
```
Backlog → To Do (5 max) → In Progress (3 max) → Review (2 max) → Done
```

---

### 3. Scrumban (híbrido)

**¿Qué es?** Mezcla de Scrum y Kanban. Planificas en sprints pero usas límites WIP de Kanban durante el sprint. Más flexible que Scrum, más estructurado que Kanban.

**Ideal para:** Equipos que quieren la estructura de Scrum pero con la flexibilidad de Kanban para priorizar cambios.

**Documentación que genera el agente:**
- Sprint Planning ligero (solo objetivos, sin estimaciones detalladas)
- Kanban board durante el sprint
- Sprint Review
- Status reports

---

## Cómo elegir

| Si tu equipo... | Elige... |
|----------------|----------|
| Tiene fechas de entrega fijas | Scrum |
| Necesita adaptarse rápido a cambios | Kanban |
| Quieres estructura pero con flexibilidad | Scrumban |
| Es una sola persona | Kanban |
| Son 3+ personas con entregas frecuentes | Scrum |
| Es mantenimiento de un sistema existente | Kanban |

---

## ¿Qué pasa si no sabes cuál elegir?

El agente te hará 3 preguntas para recomendarte:

1. **¿Trabajas solo o en equipo?**
   - Solo → Kanban o Scrumban
   - Equipo → Scrum o Scrumban

2. **¿Tienes fecha de entrega definida?**
   - Sí → Scrum
   - No → Kanban

3. **¿Los requerimientos cambian seguido?**
   - Sí → Kanban o Scrumban
   - No → Scrum

Basado en tus respuestas, el agente te recomendará la metodología y configurará la documentación automáticamente.
