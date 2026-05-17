# PROTOCOLO DE SINCRONIZACIÓN TOTAL
> Se ejecuta automáticamente al inicio y cierre de cada sesión, y ante cualquier cambio.
> Garantiza que el contexto del agente, los archivos del proyecto y el estado del código estén siempre alineados.

---

## ¿QUÉ SIGNIFICA SINCRONIZADO?

Sincronizado significa que en todo momento:

```
Lo que el agente SABE === Lo que realmente EXISTE en el proyecto
```

Si el código cambió → el contexto lo refleja.
Si se tomó una decisión → el ADR existe.
Si se cerró una fase → el checklist está marcado.
Si apareció deuda técnica → está registrada.
Si se cometió un error → está documentado para no repetirlo.

---

## CUÁNDO SE EJECUTA LA SINCRONIZACIÓN

```
TRIGGER DE SINCRONIZACIÓN
│
├── Al INICIAR una sesión nueva
├── Al CERRAR cualquier fase
├── Al completar cualquier FEATURE o PR
├── Al tomar una DECISIÓN importante
├── Al detectar o corregir un ERROR
├── Al hacer un CAMBIO en requerimientos
├── Al hacer una REFACTORIZACIÓN
├── Al agregar DEUDA TÉCNICA
└── Al CIERRE de sesión (siempre)
```

---

## PROTOCOLO DE INICIO DE SESIÓN

Cuando el usuario comparte el contexto al inicio, el agente ejecuta este protocolo:

```
SINCRONIZACIÓN DE INICIO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
1. LEER el project-context.md compartido
2. IDENTIFICAR la fase y subfase actual
3. VERIFICAR si el bucle de calidad está abierto o cerrado
4. REVISAR los últimos errores aprendidos (no repetirlos)
5. REVISAR las últimas decisiones (no contradecirlas)
6. CONFIRMAR el estado con el usuario:

"Retomo desde donde lo dejamos:
 - Fase actual: [N] — [nombre]
 - Bucle de calidad: [ABIERTO/CERRADO]
 - Último punto trabajado: [descripción]
 - Pendiente: [qué falta]
 ¿Continuamos desde aquí?"
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## PROTOCOLO DE CIERRE DE SESIÓN

Al finalizar cualquier sesión, el agente genera automáticamente:

```
RESUMEN DE SINCRONIZACIÓN — Sesión [fecha]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

ARCHIVOS DEL PROYECTO que deben actualizarse:
[ ] project-context.md
    → Actualizar: fase actual, último punto trabajado, próxima acción
    → Nuevas decisiones: [lista]
    → Nuevos errores aprendidos: [lista]
    → Métricas actualizadas: [lista]
    → Documentación de entrega: estado actualizado

[ ] decisions/ (si se tomaron decisiones)
    → Crear ADR #[N]: [título]

[ ] sessions/session-[fecha].md
    → Registrar resumen de esta sesión

ESTADO DEL CÓDIGO que debe reflejarse:
[ ] Archivos modificados: [lista de archivos]
[ ] Features completadas: [lista]
[ ] Tests agregados: [lista]
[ ] Cobertura actual: [%]
[ ] Deuda técnica nueva: [lista si existe]

DOCUMENTACIÓN DE ENTREGA:
[ ] Docs afectados por cambios de esta sesión: [lista]
    → [ ] Technical Architecture: actualizado / sin cambios
    → [ ] User Manual: actualizado / sin cambios
    → [ ] API Reference: actualizado / sin cambios
    → [ ] Deployment Guide: actualizado / sin cambios
    → [ ] Operations Guide: actualizado / sin cambios
    → [ ] Release Notes: actualizado / sin cambios
    → [ ] Admin Guide: actualizado / sin cambios
    → [ ] Security & Compliance: actualizado / sin cambios
[ ] ¿Algún documento desincronizado? [Sí / No]
    → Si Sí: lista de docs pendientes de actualizar

BUCLES DE CALIDAD:
[ ] Fase [N]: [ABIERTO/CERRADO]
    Ítems pendientes si abierto: [lista]

PRÓXIMA SESIÓN debe comenzar por:
→ [acción concreta más importante]

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Copia este resumen al project-context.md antes de cerrar.
```

---

## SINCRONIZACIÓN DE ARCHIVOS DE PROYECTO

### Cuándo actualizar cada archivo

| Archivo | Se actualiza cuando |
|---------|-------------------|
| `project-context.md` | Cierre de sesión, cambio de fase, nueva decisión, nuevo error |
| `decisions/ADR-N.md` | Cada decisión arquitectónica importante |
| `sessions/session-fecha.md` | Cierre de cada sesión |
| `docs/architecture/` | Cambio de arquitectura o diseño |
| `docs/delivery/01-technical-architecture.md` | Nueva ADR, cambio de stack, cambio de arquitectura |
| `docs/delivery/02-user-manual.md` | Nueva feature completada, cambio de UI |
| `docs/delivery/03-api-reference.md` | Nuevo endpoint, cambio de contrato de API |
| `docs/delivery/04-deployment-guide.md` | Cambio en infraestructura, CI/CD, variables de entorno |
| `docs/delivery/05-operations-guide.md` | Cambio en monitoreo, alertas, runbook |
| `docs/delivery/06-release-notes.md` | Nueva versión, feature completada, bug corregido |
| `docs/delivery/07-admin-guide.md` | Cambio en roles, permisos, config del sistema |
| `docs/delivery/08-security-compliance.md` | Cambio en políticas de seguridad, nuevo compliance |
| `GitHub Issues/PRs/Milestones` | Nueva feature, bug, cambio de fase, deploy |

### Campos que SIEMPRE deben estar sincronizados en project-context.md

```
VERIFICACIÓN DE SINCRONIZACIÓN DEL CONTEXTO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
[ ] Fecha de última actualización = hoy
[ ] Fase actual = fase donde realmente estamos
[ ] Bucle de calidad = estado real (no lo que quisiéramos)
[ ] Próxima acción = concreta, no vaga
[ ] Decisiones recientes = todas documentadas
[ ] Errores recientes = todos documentados
[ ] Métricas = actualizadas con datos reales
[ ] Stack tecnológico = refleja lo que realmente se usa
[ ] User stories = estado real (no el planificado)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## SINCRONIZACIÓN DE ARCHIVOS DE DESARROLLO

### Mapa de sincronización: acción → archivos que deben actualizarse

**Cuando se agrega una nueva feature:**
```
FEATURE COMPLETADA → sincronizar:
[ ] Código fuente (el feature en sí)
[ ] Tests unitarios del feature
[ ] Tests de integración si aplica
[ ] README o docs si hay API pública nueva
[ ] project-context.md → user story marcada como completada
[ ] project-context.md → cobertura de pruebas actualizada
[ ] project-context.md → métricas actualizadas
[ ] docs/delivery/02-user-manual.md → nueva funcionalidad documentada
[ ] docs/delivery/03-api-reference.md → nuevos endpoints documentados (si aplica)
[ ] docs/delivery/06-release-notes.md → entrada agregada
```

**Cuando se hace una refactorización:**
```
REFACTORIZACIÓN → sincronizar:
[ ] Código refactorizado
[ ] Tests existentes (verificar que pasan)
[ ] project-context.md → deuda técnica reducida si aplica
[ ] project-context.md → error aprendido si hubo problema
[ ] ADR si el refactor implica decisión arquitectónica
[ ] docs/delivery/01-technical-architecture.md → actualizado si cambia estructura
```

**Cuando se corrige un bug:**
```
BUG CORREGIDO → sincronizar:
[ ] Código corregido
[ ] Test que reproduce el bug (antes de la corrección)
[ ] project-context.md → error aprendido #N documentado
[ ] project-context.md → deuda técnica actualizada si el bug era síntoma de ella
[ ] docs/delivery/06-release-notes.md → corrección documentada
[ ] docs/delivery/05-operations-guide.md → runbook actualizado si el bug era operacional
[ ] docs/delivery/02-user-manual.md → FAQ actualizado si el bug afectaba al usuario
```

**Cuando cambian los requerimientos:**
```
CAMBIO DE REQUERIMIENTO → sincronizar:
[ ] project-context.md → user story actualizada o nueva
[ ] project-context.md → backlog repriorizado
[ ] Tests que cubren el nuevo comportamiento
[ ] ADR si el cambio implica decisión de diseño
[ ] Docs de API si cambia la interfaz pública
[ ] project-context.md → riesgos actualizados si el cambio introduce nuevos
[ ] docs/delivery/02-user-manual.md → secciones afectadas actualizadas
[ ] docs/delivery/06-release-notes.md → cambio registrado
```

---

## DETECTOR DE DESINCRONIZACIÓN

El agente detecta automáticamente cuando algo está desincronizado y lo señala:

```
⚠️ DESINCRONIZACIÓN DETECTADA (incluyendo GitHub)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Problema: [qué está desincronizado]
Detectado porque: [evidencia]

Lo que dice el contexto: [valor en project-context.md]
Lo que es la realidad: [valor real]
GitHub Issues: [estado actual en GitHub vs lo que debería ser]

Para sincronizar:
1. [paso concreto]
2. [paso concreto]

¿Lo sincronizamos ahora?
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### Señales de desincronización que el agente siempre detecta

- El contexto dice "fase 2" pero el usuario menciona código que corresponde a fase 3
- El contexto dice cobertura 80% pero no se mencionaron tests en las últimas sesiones
- Hay una decisión tomada en conversación pero no hay ADR correspondiente
- El usuario menciona un bug que debería estar en "errores aprendidos" pero no está
- El stack tecnológico en el contexto no coincide con el código que se comparte
- Hay deuda técnica mencionada en conversación que no está registrada
- El bucle de calidad figura como cerrado pero hay ítems sin resolver
- Hay un issue de GitHub cerrado pero la feature no está completada en el proyecto
- Hay un PR mergeado pero no está registrado en las Release Notes
- El milestone activo de GitHub no coincide con la fase actual del proyecto
- Hay cambios en el código sin issue vinculado en GitHub
- Hay un release en GitHub que no corresponde al estado actual del proyecto
- Los documentos de entrega no reflejan cambios recientes en el proyecto
- Hay una nueva feature completada pero el Manual de Usuario no la menciona
- Hay un nuevo endpoint pero la API Reference no lo incluye
- Hay una nueva ADR pero el Documento de Arquitectura no la referencia

---

## REGLA DORADA DE SINCRONIZACIÓN

```
ANTES de cada respuesta, el agente verifica internamente:

"¿Lo que voy a decir o generar es consistente con
 todo lo que está documentado en el proyecto?"

Si NO → sincronizar primero, responder después.
Si SÍ → proceder con confianza.
```

**Nunca hay dos verdades en el proyecto.**
El `project-context.md` y el código son la única fuente de verdad.
Si difieren → se corrige la desincronización antes de continuar.
