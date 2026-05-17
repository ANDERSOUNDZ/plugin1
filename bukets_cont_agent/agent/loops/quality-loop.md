# BUCLE DE CALIDAD UNIVERSAL
> El corazón del framework. Se ejecuta en cada fase, subfase, cambio y revisión.

---

## ¿Qué es el bucle de calidad?

Es el proceso que garantiza que **nada avanza sin estar bien hecho**. No es burocracia — es la diferencia entre software que dura y software que se convierte en pesadilla técnica.

El agente no puede avanzar a la siguiente fase si el bucle está abierto.

---

## EL BUCLE

```
┌─────────────────────────────────────────────────────┐
│                  BUCLE DE CALIDAD                    │
│                                                      │
│                  ┌─────────┐                         │
│                  │ ENTRAR  │                         │
│                  │ A FASE  │                         │
│                  └────┬────┘                         │
│                       │                              │
│                  ┌────▼────┐                         │
│                  │EJECUTAR │ ← Hacer la actividad    │
│                  └────┬────┘                         │
│                       │                              │
│                  ┌────▼────────────┐                 │
│                  │    REVISAR      │                 │
│                  │ ¿Cumple calidad?│                 │
│                  └────┬────────────┘                 │
│                       │                              │
│               NO ◄────┤────► SÍ                      │
│               │        │                             │
│          ┌────▼───┐  ┌─▼──────────┐                 │
│          │CORREGIR│  │  VALIDAR   │                 │
│          │ + DOC  │  │ checklist  │                 │
│          └────┬───┘  └─┬──────────┘                 │
│               │        │                             │
│               └────────┘                             │
│                       │                              │
│                  ┌────▼────┐                         │
│                  │DOCUMENTAR│                        │
│                  └────┬────┘                         │
│                       │                              │
│                  ┌────▼────┐                         │
│                  │ AVANZAR │                         │
│                  └─────────┘                         │
└─────────────────────────────────────────────────────┘
```

---

## CRITERIOS DE CALIDAD POR DIMENSIÓN

### Dimensión 1 — Corrección
¿El artefacto hace lo que debe hacer?
- Cumple los criterios de aceptación definidos
- Maneja casos límite y errores
- No introduce regresiones

### Dimensión 2 — Claridad
¿Es fácil de entender para alguien nuevo?
- Nombres descriptivos y consistentes
- Estructura lógica y predecible
- Documentación donde agrega valor real

### Dimensión 3 — Mantenibilidad
¿Se puede modificar sin miedo?
- Bajo acoplamiento entre módulos
- Alta cohesión dentro de cada módulo
- Pruebas que dan confianza para cambiar

### Dimensión 4 — Seguridad
¿Es seguro en condiciones reales?
- Inputs validados y sanitizados
- Sin datos sensibles expuestos
- Principio de mínimo privilegio

### Dimensión 5 — Rendimiento
¿Funciona bien bajo carga real?
- Sin cuellos de botella obvios
- Queries optimizadas
- Sin memory leaks

### Dimensión 6 — Documentación
¿La documentación de entrega está sincronizada con el proyecto?
- Todos los documentos de entrega están actualizados con el estado actual
- No hay placeholders ni secciones vacías en los documentos activos
- La información en los documentos es consistente con el código y configuración real
- Las decisiones recientes están reflejadas en los documentos afectados
- Los cambios de esta fase están documentados en Release Notes

---

## CUÁNDO EJECUTAR EL BUCLE

| Evento | Ejecutar bucle |
|--------|---------------|
| Cierre de fase | ✅ Siempre |
| Merge de feature/PR | ✅ Siempre |
| Cambio en requerimientos | ✅ Siempre |
| Refactorización | ✅ Siempre |
| Optimización de rendimiento | ✅ Siempre |
| Corrección de bug | ✅ Siempre |
| Actualización de dependencias | ✅ Siempre |
| Cambio en documentación de entrega | ✅ Siempre (validar docs) |
| Respuesta conversacional simple | ❌ No aplica |

---

## PROTOCOLO DE BLOQUEO

Cuando el bucle está abierto, el agente comunica así:

```
🔴 BUCLE DE CALIDAD ABIERTO
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Fase: [nombre de la fase]
No podemos avanzar porque:

[ ] [ítem pendiente 1]
    → Por qué importa: [explicación]
    → Cómo resolverlo: [sugerencia concreta]

[ ] [ítem pendiente 2]
    → Por qué importa: [explicación]
    → Cómo resolverlo: [sugerencia concreta]

¿Empezamos por cuál?
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## PROTOCOLO DE CIERRE

Cuando el bucle se cierra exitosamente:

```
✅ BUCLE DE CALIDAD CERRADO — Fase [N] completada
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Todos los criterios verificados:
[✅] [ítem 1]
[✅] [ítem 2]
[✅] [ítem 3]

📊 Métricas de esta fase:
[métricas relevantes]

📝 Decisiones documentadas: [N]
⚠️ Riesgos registrados: [N]

Listo para avanzar a: Fase [N+1] — [nombre]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## ESCALA DE SEVERIDAD DE HALLAZGOS

Cuando el agente detecta un problema durante la revisión:

| Nivel | Símbolo | Definición | Acción |
|-------|---------|-----------|--------|
| Crítico | 🔴 | Rompe funcionalidad o compromete seguridad | Bloquea el avance. Resolver antes de todo. |
| Alto | 🟠 | Viola principios de diseño o calidad importantes | Resolver en el mismo ciclo. |
| Medio | 🟡 | Mejora importante pero no urgente | Resolver en el próximo sprint. |
| Bajo | 🔵 | Sugerencia de mejora opcional | Considerar cuando haya tiempo. |
| Positivo | ✅ | Buena práctica detectada | Documenta como patrón a replicar. |
