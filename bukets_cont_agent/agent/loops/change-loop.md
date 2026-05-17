# BUCLE DE CAMBIOS Y REFACTORIZACIONES
> Se activa ante cualquier cambio, refactorización, optimización o corrección.

---

## FLUJO OBLIGATORIO ANTE CUALQUIER CAMBIO

```
CAMBIO SOLICITADO
       │
  ┌────▼──────────────────┐
  │  1. ANALIZAR           │
  │  ¿Qué fase afecta?    │
  │  ¿Es nuevo o cambia   │
  │  algo existente?      │
  └────┬──────────────────┘
       │
  ┌────▼──────────────────┐
  │  2. MEMORIA            │
  │  ¿Hay decisiones      │
  │  previas relacionadas?│
  │  ¿Errores aprendidos? │
  └────┬──────────────────┘
       │
  ┌────▼──────────────────┐
  │  3. IMPACTO            │
  │  ¿Qué módulos afecta? │
  │  ¿Qué se puede romper?│
  │  ¿Qué pruebas cubren? │
  └────┬──────────────────┘
       │
  ┌────▼──────────────────┐
  │  4. PLANIFICAR         │
  │  Pasos concretos      │
  │  para hacerlo bien    │
  └────┬──────────────────┘
       │
  ┌────▼──────────────────┐
  │  5. EJECUTAR           │
  │  Con estándares       │
  │  de calidad activos   │
  └────┬──────────────────┘
       │
  ┌────▼──────────────────┐
  │  6. BUCLE DE CALIDAD  │
  │  Checklist de la fase │
  │  afectada             │
  └────┬──────────────────┘
       │
   ┌────▼──────────────────┐
   │  7. DOCUMENTAR         │
   │  ADR si es decisión   │
   │  Error aprendido si   │
   │  hubo problema        │
   │  Docs de entrega      │
   │  afectados            │
   │  Sprint docs          │
   │  (si aplica)          │
   └────┬──────────────────┘
       │
  ┌────▼──────────────────┐
  │  8. VALIDAR            │
  │  Pruebas de regresión │
  │  Nada se rompió       │
  └────┬──────────────────┘
       │
  ┌────▼──────────────────┐
  │  CAMBIO COMPLETADO    │
  └───────────────────────┘
```

---

## TIPOS DE CAMBIO Y SU TRATAMIENTO

### Refactorización
**Definición:** Mejorar el código sin cambiar su comportamiento externo.
**Riesgo principal:** Introducir regresiones sin querer.

Antes de refactorizar:
- [ ] ¿Existen pruebas que cubran el código a refactorizar?
- [ ] Si no hay pruebas, ¿las escribimos antes de tocar el código?
- [ ] ¿Se entiende exactamente el comportamiento actual?

Durante:
- Cambios pequeños e incrementales
- Ejecutar pruebas después de cada paso
- Commit por cada refactorización atómica

Después:
- [ ] Las pruebas existentes siguen pasando
- [ ] El comportamiento externo es idéntico
- [ ] El código es notablemente más limpio/simple
- [ ] Docs de entrega afectados actualizados (si cambió estructura o interfaces)
- [ ] GitHub sincronizado: issue actualizado, PR mergeado si aplica

### Optimización de rendimiento
**Definición:** Mejorar velocidad, memoria o recursos sin cambiar funcionalidad.
**Riesgo principal:** Optimizar algo que no era el cuello de botella real.

Antes de optimizar:
- [ ] ¿Hay evidencia medida de que esto es un problema? (no intuición)
- [ ] ¿Qué dice el profiler o las métricas?
- [ ] ¿Cuál es el baseline de rendimiento actual?

Durante:
- Optimizar solo lo que las métricas señalan
- Medir antes y después de cada cambio

Después:
- [ ] Mejora medida y documentada (N% más rápido)
- [ ] Sin regresiones funcionales
- [ ] La optimización es comprensible para el equipo
- [ ] GitHub sincronizado: issue actualizado, PR mergeado si aplica

### Corrección de bug
**Definición:** Corregir comportamiento incorrecto del sistema.
**Riesgo principal:** Corregir el síntoma sin resolver la causa raíz.

Antes de corregir:
- [ ] ¿Se entendió la causa raíz? (no solo el síntoma)
- [ ] ¿Se tiene un caso de prueba que reproduzca el bug?
- [ ] ¿Qué otros módulos podrían tener el mismo problema?

Durante:
- Primero escribir la prueba que falla por el bug
- Luego corregir hasta que la prueba pase

Después:
- [ ] La prueba que reproducía el bug ahora pasa
- [ ] Las pruebas existentes siguen pasando
- [ ] Se documentó en "Errores Aprendidos" si fue significativo
- [ ] Release Notes actualizadas con la corrección
- [ ] Manual de Usuario actualizado si el bug afectaba flujos de usuario
- [ ] GitHub sincronizado: issue cerrado, PR mergeado

### Cambio de requerimientos
**Definición:** El usuario o negocio cambia qué debe hacer el sistema.
**Riesgo principal:** Impacto no evaluado en todo lo que ya existe.

Antes de implementar:
- [ ] ¿El cambio está documentado y aprobado?
- [ ] ¿Qué user stories existentes se ven afectadas?
- [ ] ¿Qué pruebas hay que actualizar?
- [ ] ¿Hay decisiones arquitectónicas que deban revisarse?
- [ ] ¿Qué documentos de entrega se ven afectados?
    - [ ] Manual de Usuario
    - [ ] API Reference
    - [ ] Documento de Arquitectura
    - [ ] Release Notes
    - [ ] Otros docs afectados

Durante:
- Actualizar user stories afectadas con los nuevos criterios
- Revisar si el cambio requiere nueva ADR o modificar una existente
- Ejecutar pruebas existentes para verificar que no se rompió nada antes del cambio

Después:
- [ ] User stories actualizadas o creadas reflejan el nuevo requerimiento
- [ ] ADR documentada si el cambio afecta decisiones arquitectónicas
- [ ] Las pruebas existentes siguen pasando
- [ ] Nuevas pruebas escritas para el nuevo comportamiento (si aplica)
- [ ] Docs de entrega afectados actualizados
- [ ] project-context.md actualizado: riesgos, decisiones, métricas
- [ ] GitHub sincronizado: issue creado/actualizado, milestone ajustado



### Merge de feature / Pull Request
**Definición:** Integrar código nuevo a la rama principal.
**Riesgo principal:** Conflictos de integración o regresiones no detectadas.

Antes del merge:
- [ ] Code review aprobado (mínimo 1 revisor)
- [ ] Todas las pruebas unitarias pasan
- [ ] Pruebas de integración pasan (si existen)
- [ ] No hay conflictos con la rama base
- [ ] La feature completa su user story correspondiente

Durante:
- Usar squash merge o merge convencional según convención del equipo
- Ejecutar suite completa de pruebas post-merge

Después:
- [ ] La rama feature se eliminó (si aplica)
- [ ] Release Notes actualizadas con la nueva feature
- [ ] Docs de entrega afectados actualizados (Manual de Usuario, API Reference)
- [ ] GitHub sincronizado: PR mergeado, issue cerrado

### Actualización de dependencias
**Definición:** Actualizar librerías, paquetes o herramientas del proyecto.
**Riesgo principal:** Breaking changes no detectados en dependencias actualizadas.

Antes de actualizar:
- [ ] ¿Hay un changelog de la dependencia que indique breaking changes?
- [ ] ¿La versión actual está soportada o tiene vulnerabilidades conocidas?
- [ ] ¿Existen pruebas que cubran el código que usa esta dependencia?

Durante:
- Actualizar una dependencia a la vez (no varias en el mismo commit)
- Ejecutar pruebas después de cada actualización

Después:
- [ ] Las pruebas existentes siguen pasando
- [ ] No hay funcionalidades rotas por el cambio de versión
- [ ] Se documentó en Release Notes si hubo cambio mayor
- [ ] docs/delivery/04-deployment-guide.md actualizado si cambian requisitos de infraestructura
- [ ] GitHub sincronizado: issue creado para seguimiento (si es cambio significativo)

### Cambio en documentación de entrega
**Definición:** Modificar, corregir o completar documentos de entrega del proyecto.
**Riesgo principal:** Información desactualizada o inconsistente entre documentos.

Antes del cambio:
- [ ] ¿Qué documentos específicos se modifican?
- [ ] ¿La fuente de la nueva información está verificada (código, configuración, ADR)?
- [ ] ¿Hay otros documentos que referencien la misma información y deban actualizarse también?

Durante:
- Mantener el mismo formato y tono del documento existente
- Actualizar la fecha de última modificación

Después:
- [ ] El documento modificado pasó el validador de documentación (documentation-validator.md)
- [ ] Todos los documentos que referencian la misma información están consistentes
- [ ] project-context.md refleja el nuevo estado del documento
- [ ] GitHub sincronizado: issue de documentación actualizado si existe

---

## EVALUACIÓN DE IMPACTO

Cuando se solicita un cambio, el agente evalúa:

```
EVALUACIÓN DE IMPACTO — [nombre del cambio]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Tipo de cambio: [Refactorización / Optimización / Bug fix / Requerimiento]
Fase SDLC afectada: [fase]

Módulos afectados:
- [módulo 1] → [cómo se ve afectado]
- [módulo 2] → [cómo se ve afectado]

Pruebas que podrían fallar:
- [suite o test específico]

Riesgos del cambio:
🔴 [riesgo crítico si existe]
🟡 [riesgo medio si existe]

Decisiones previas relacionadas:
- ADR #[N]: [breve descripción de cómo aplica]

Errores aprendidos relacionados:
- Error #[N]: [breve descripción de cómo aplica]

Esfuerzo estimado: [XS / S / M / L / XL]
Recomendación: [proceder / pausar y analizar más / escalar]
Sprint docs afectados: [sprint-plan / sprint-backlog / sprint-review / status-report]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```
