# CONTEXTO DEL PROYECTO — [Nombre del Proyecto]
> Archivo vivo: actualízalo al cierre de cada fase y con cada decisión importante.
> El agente lee este archivo para mantener su memoria entre sesiones.
>
> 💡 **Ejemplo:** Revisa `examples/example-project-context.md` para ver un contexto real completado.

---

## ESTADO ACTUAL

```
Fecha de inicio:        [fecha — ej: 2026-05-01]
Última actualización:   [fecha — ej: 2026-05-15]
Fase actual:            [0-7] — [nombre de la fase — ej: 2 — Diseño y Arquitectura]
Subfase:                [descripción concreta — ej: Definiendo modelo de datos]
Bucle de calidad:       [ABIERTO / CERRADO]
Próxima acción:         [acción específica — ej: Presentar ADR #1 al equipo]
GitHub:
  Repo:                 [URL del repositorio — ej: https://github.com/usuario/proyecto]
  Issues abiertos:      [N]
  PRs abiertos:         [N]
  Última release:       [vX.X.X]
Metodología:            [Scrum / Kanban / Scrumban]
Sprint actual:          [N]
  Goal:                 [objetivo del sprint]
  Inicio:               [fecha — ej: 2026-05-18]
  Fin:                  [fecha — ej: 2026-05-31]
```

---

## RESUMEN DEL PROYECTO

```
Nombre:             [nombre del proyecto]
Problema central:   [descripción del problema que resuelve]
Usuarios objetivo:  [perfil del usuario]
MVP definido:       [qué incluye la primera versión]
Fuera del MVP:      [qué queda para después]
Modelo de trabajo:  [Scrum / Kanban / otro]
```

---

## STACK TECNOLÓGICO ACORDADO

```
Frontend:    [tecnología + versión + justificación]
Backend:     [tecnología + versión + justificación]
Base de datos: [tecnología + justificación]
Infraestructura: [cloud/on-premise + herramientas]
CI/CD:       [herramienta]
Testing:     [framework de pruebas]
Monitoreo:   [herramienta]
```

---

## EQUIPO

```
Rol              | Nombre       | Responsabilidad
─────────────────|──────────────|──────────────────────
[Rol]            | [Nombre]     | [Responsabilidad]
```

---

## DECISIONES TOMADAS

### Decisión #1
```
Fecha: [fecha]
Fase: [fase donde se tomó]
Decisión: [qué se decidió]
Alternativas descartadas: [otras opciones y por qué no]
Justificación: [razón de la elección]
Impacto: [qué afecta]
Revisable cuando: [condición]
```

### Decisión #2
```
[mismo formato]
```

---

## ERRORES APRENDIDOS

### Error #1
```
Fecha: [fecha]
Fase: [en qué fase ocurrió]
Error: [qué salió mal]
Causa raíz: [por qué ocurrió]
Solución aplicada: [cómo se resolvió]
Prevención futura: [qué hacer diferente]
```

---

## PATRONES DESCUBIERTOS

### Patrón #1
```
Contexto: [situación donde se encontró]
Patrón: [descripción]
Beneficio: [qué problema resuelve]
Aplicación: [cómo usarlo en este proyecto]
```

---

## REQUERIMIENTOS

### Funcionales (User Stories)

#### Historia #1: [Nombre]
```
Como [usuario]
Quiero [acción]
Para [beneficio]

Criterios de aceptación:
- [ ] Dado [contexto], cuando [acción], entonces [resultado]
- [ ] [criterio 2]

Estimación: [XS/S/M/L/XL]
Prioridad: [Must/Should/Could/Won't]
Estado: [Pendiente / En progreso / Completado / Bloqueado]
```

### No Funcionales

```
Rendimiento:    [tiempo de respuesta máximo esperado]
Disponibilidad: [uptime requerido]
Seguridad:      [requisitos de seguridad]
Escalabilidad:  [usuarios concurrentes esperados]
```

---

## RIESGOS

### Matriz de Riesgos

| # | Riesgo | Probabilidad | Impacto | Mitigación | Estado |
|---|--------|-------------|---------|------------|--------|
| 1 | [riesgo] | Alta/Media/Baja | Alto/Medio/Bajo | [cómo se mitiga] | Activo/Resuelto |

---

## DEUDA TÉCNICA

| # | Descripción | Tipo | Impacto | Esfuerzo | Prioridad | Estado |
|---|-------------|------|---------|----------|-----------|--------|
| 1 | [deuda] | Código/Arquitectura/Pruebas | Alto/Medio/Bajo | [horas] | Alta/Media/Baja | Pendiente |

---

## ESTADO DE FASES

| Fase | Nombre | Estado | Fecha inicio | Fecha cierre | Bucle QA |
|------|--------|--------|-------------|-------------|----------|
| 0 | Entrevista | [Pendiente/Completada] | [fecha] | [fecha] | [ABIERTO/CERRADO] |
| 1 | Requerimientos | [Pendiente/En progreso/Completada] | - | - | - |
| 2 | Diseño | Pendiente | - | - | - |
| 3 | Desarrollo | Pendiente | - | - | - |
| 4 | QA | Pendiente | - | - | - |
| 5 | Despliegue | Pendiente | - | - | - |
| 6 | Mantenimiento | Pendiente | - | - | - |
| 7 | Documentación de Entrega | Pendiente | - | - | - |

---

## DOCUMENTACIÓN DE ENTREGA

### Estado de documentos

| Documento | Estado | Última actualización | Validado |
|-----------|--------|---------------------|----------|
| Arquitectura Técnica | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |
| Manual de Usuario | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |
| API Reference | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |
| Guía de Despliegue | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |
| Guía de Operaciones | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |
| Release Notes | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |
| Guía de Administración | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |
| Seguridad y Compliance | [Borrador / En progreso / Listo para validación / Validado] | [fecha] | [Sí / No] |

### Última sincronización de documentación

```
Fecha: [fecha]
Docs actualizados: [lista de documentos actualizados en esta sesión]
Docs desincronizados: [lista si hay]
```

---

## MÉTRICAS DE CALIDAD

| Métrica | Objetivo | Actual | Estado |
|---------|----------|--------|--------|
| Cobertura de pruebas | ≥ 70% | [%] | 🔴/🟡/🟢 |
| Bugs críticos abiertos | 0 | [N] | 🔴/🟡/🟢 |
| ADRs documentados | ≥ 1 por decisión | [N] | 🔴/🟡/🟢 |
| Deuda técnica | Baja | [nivel] | 🔴/🟡/🟢 |
| User stories completadas | [N]/[total] | [N]/[total] | 🔴/🟡/🟢 |
| Documentos de entrega completos | 8 | [N] | 🔴/🟡/🟢 |
| Docs sincronizados | 8 | [N] | 🔴/🟡/🟢 |

---

## LOG DE SESIONES

### Sesión [fecha]
```
Duración: [tiempo]
Fase trabajada: [fase]
Qué se hizo: [resumen]
Decisiones tomadas: [lista]
Próxima sesión: [qué continuar]
```
