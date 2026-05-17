# VALIDADOR DE DOCUMENTACIÓN DE ENTREGA
> El agente usa esta guía para validar cada documento de entrega antes de incluirlo en el paquete final.

---

## PROCESO DE VALIDACIÓN DE DOCUMENTACIÓN

El agente valida cada documento en este orden:

```
1. COMPLETITUD    → ¿Todas las secciones tienen contenido?
2. CLARIDAD       → ¿Es entendible para su audiencia objetivo?
3. CONSISTENCIA   → ¿Términos y formato uniformes?
4. ACTUALIZACIÓN  → ¿Refleja el estado actual del proyecto?
5. TRAZABILIDAD   → ¿Cada afirmación se conecta con una fuente real?
6. SIN BASURA     → ¿Sin placeholders, borradores o información obsoleta?
```

---

## CHECKLIST DE VALIDACIÓN COMPLETO

### Completitud
- [ ] No hay secciones vacías o con "[completar]" / "[pendiente]"
- [ ] No hay títulos sin contenido debajo
- [ ] Cada sección obligatoria según la plantilla está presente
- [ ] Todos los ejemplos incluyen datos de demostración reales

### Claridad
- [ ] El lenguaje es apropiado para la audiencia objetivo del documento
- [ ] No hay jerga técnica sin explicación (para documentos de usuario)
- [ ] Las instrucciones son paso a paso y secuenciales
- [ ] Los párrafos no son excesivamente largos (< 10 líneas)
- [ ] Cada sección comienza con una frase que explica su propósito

### Consistencia
- [ ] El nombre del proyecto es el mismo en todo el documento
- [ ] Los términos técnicos se usan de forma consistente (no "usuario" y "user" mezclados)
- [ ] El formato de fechas es uniforme (YYYY-MM-DD en todo el documento)
- [ ] Los ejemplos de código siguen el mismo estilo
- [ ] Los títulos y subtítulos siguen la misma jerarquía (##, ###, ####)

### Actualización
- [ ] Las fechas en el documento son coherentes con la línea de tiempo del proyecto
- [ ] Las versiones mencionadas coinciden con la versión actual del proyecto
- [ ] Las URLs o referencias a servicios externos son válidas
- [ ] Las capturas de pantalla textuales describen el estado actual de la UI
- [ ] Los endpoints de API reflejan la implementación real (no el diseño inicial)

### Trazabilidad
- [ ] Las decisiones arquitectónicas referencian ADRs específicos (ADR #N)
- [ ] Las funcionalidades descritas referencian user stories (Historia #N)
- [ ] Las correcciones mencionadas referencian errores aprendidos (Error #N)
- [ ] Los cambios en release notes referencian su fuente (commit, ADR, user story)
- [ ] Cada afirmación técnica puede verificarse contra el código o configuración

### Sin basura
- [ ] Sin secciones de documentación interna (notas para desarrolladores)
- [ ] Sin comentarios de "TODO" o "FIXME"
- [ ] Sin información duplicada entre documentos (cada hecho en un solo lugar)
- [ ] Sin información de versiones anteriores del documento
- [ ] Sin datos sensibles (contraseñas, tokens, IPs internas)

---

## ESCALA DE HALLAZGOS EN DOCUMENTACIÓN

| Nivel | Símbolo | Definición | Acción |
|-------|---------|-----------|--------|
| Crítico | 🔴 | Información incorrecta o que puede causar daño | Bloquea la entrega. Corregir antes de continuar. |
| Alto | 🟠 | Sección faltante o desactualizada críticamente | Completar antes de cerrar la fase. |
| Medio | 🟡 | Placeholder o inconsistencia menor | Resolver antes de la entrega final. |
| Bajo | 🔵 | Mejora de estilo o formato opcional | Considerar para próxima iteración. |
| Positivo | ✅ | Buena práctica de documentación detectada | Documentar como referencia futura. |

---

## FORMATO DE REPORTE DE VALIDACIÓN

```
VALIDACIÓN DE DOCUMENTACIÓN — [nombre del documento]
Fecha: [fecha]
Validador: SDLC Agent
┌─────────────────────────────────────────────────────────────┐

✅ COMPLETITUD: [N] secciones de [N] completas
   🔴 Sección [nombre]: [detalle del problema] (crítico)

✅ CLARIDAD: [APROBADO / REQUIERE MEJORAS]
   🟡 [observación de claridad]

✅ CONSISTENCIA: [APROBADO / REQUIERE MEJORAS]
   🔵 [sugerencia de consistencia]

✅ ACTUALIZACIÓN: [APROBADO / DESACTUALIZADO]
   🟠 Sección [nombre]: [detalle de desactualización]

✅ TRAZABILIDAD: [N] referencias de [N] trazables
   🟡 [referencia sin fuente]

✅ SIN BASURA: [APROBADO / REQUIERE LIMPIEZA]

└─────────────────────────────────────────────────────────────┘
VEREDICTO: [APROBADO / CAMBIOS REQUERIDOS / RECHAZADO]

Pendientes antes de aprobar:
- [ ] [ítem bloqueante 1]
- [ ] [ítem bloqueante 2]
```

---

## VALIDACIÓN CRUZADA ENTRE DOCUMENTOS

El agente también verifica la consistencia **entre** documentos:

```
VALIDACIÓN CRUZADA
[ ] El stack tecnológico en Arquitectura Técnica coincide con Guía de Despliegue
[ ] El modelo de datos en Arquitectura Técnica coincide con API Reference
[ ] Las funcionalidades en Manual de Usuario coinciden con user stories completadas
[ ] Los endpoints en API Reference existen en el código (no son diseño no implementado)
[ ] Las versiones en Release Notes coinciden con el historial de cambios
[ ] Las variables de entorno en Guía de Despliegue coinciden con Guía de Operaciones
```

---

## REGLAS ABSOLUTAS DE DOCUMENTACIÓN

1. **No se entrega un documento con placeholders.** Si falta información, se completa o se pregunta al usuario antes de la entrega.
2. **No hay dos versiones de la misma verdad.** Si un dato aparece en múltiples documentos, debe ser idéntico.
3. **Audiencia primero.** El mismo hecho se escribe diferente según el documento. Una decisión técnica en el Manual de Usuario se simplifica; en Arquitectura se detalla con justificación.
4. **La documentación se sincroniza siempre.** Si el proyecto cambia, los documentos afectados se actualizan inmediatamente (nunca esperar a Fase 7).
5. **Sin datos sensibles.** Ningún documento de entrega contiene contraseñas, tokens, IPs internas o credenciales de ningún tipo.
