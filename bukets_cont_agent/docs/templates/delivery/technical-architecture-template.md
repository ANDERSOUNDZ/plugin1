# Documento de Arquitectura Técnica — [Nombre del Proyecto]

> **Versión:** [versión]
> **Fecha:** [YYYY-MM-DD]
> **Cliente:** [nombre del cliente]
> **Clasificación:** [Público / Interno / Confidencial]
>
> 📝 **¿Cómo se genera este documento?**
> El agente SDLC lo construye automáticamente durante las Fases 2 y 7.
> Extrae la información de las ADRs (decisiones arquitectónicas), el stack
> tecnológico acordado, el modelo de datos y los flujos del sistema.
>
> ✅ **¿Qué debe cumplir para estar completo?**
> - Cada decisión técnica debe referenciar su ADR correspondiente
> - El stack tecnológico debe tener justificación (no solo listar tecnologías)
> - Los diagramas deben ser descriptivos (textuales), no requieren herramienta gráfica

---

## 1. Resumen Ejecutivo

[Descripción de alto nivel de la arquitectura del sistema en 3-5 párrafos. Qué problema resuelve, cómo está estructurado, qué decisiones clave se tomaron.]

---

## 2. Diagrama de Componentes

```
[Diagrama textual de la arquitectura]

Ejemplo:
┌──────────┐     ┌──────────┐     ┌──────────┐
│  Cliente  │────▶│   API    │────▶│  Base de │
│ (React)  │◀────│ (Node)   │◀────│  Datos   │
└──────────┘     └────┬─────┘     │ (Postgres)│
                      │           └──────────┘
                      ▼
               ┌──────────┐
               │ Servicio │
               │ Externo  │
               └──────────┘
```

### Capas del sistema

| Capa | Tecnología | Responsabilidad |
|------|-----------|----------------|
| [Frontend] | [tecnología] | [responsabilidad] |
| [Backend] | [tecnología] | [responsabilidad] |
| [Base de datos] | [tecnología] | [responsabilidad] |
| [Infraestructura] | [tecnología] | [responsabilidad] |

---

## 3. Stack Tecnológico Detallado

| Componente | Tecnología | Versión | Justificación |
|-----------|-----------|---------|--------------|
| Frontend | [tecnología] | [versión] | [por qué se eligió] |
| Backend | [tecnología] | [versión] | [por qué se eligió] |
| Base de datos | [tecnología] | [versión] | [por qué se eligió] |
| Cache | [tecnología] | [versión] | [por qué se eligió] |
| CI/CD | [tecnología] | [versión] | [por qué se eligió] |
| Monitoreo | [tecnología] | [versión] | [por qué se eligió] |
| Hosting | [plataforma] | - | [por qué se eligió] |

---

## 4. Modelo de Datos

### Entidades principales

```
[Entidad 1]
├── id: UUID (PK)
├── nombre: VARCHAR(255)
├── created_at: TIMESTAMP
└── updated_at: TIMESTAMP

[Entidad 2]
├── id: UUID (PK)
├── entidad_1_id: UUID (FK → Entidad 1)
├── ...
```

### Relaciones

```
[Entidad 1] 1 ──── * [Entidad 2]
[Entidad 2] * ──── 1 [Entidad 3]
```

### Índices principales

| Tabla | Índice | Tipo | Propósito |
|-------|--------|------|-----------|
| [tabla] | [columna] | [BTREE/GIN/etc.] | [por qué] |

---

## 5. Decisiones Arquitectónicas

| # | Decisión | ADR | Estado |
|---|----------|-----|--------|
| 1 | [decisión] | ADR #1 | [Aceptada] |
| 2 | [decisión] | ADR #2 | [Aceptada] |

### Compilación de ADRs

```
ADR #1 — [título]
Decisión: [qué se decidió]
Contexto: [por qué se necesitaba]
Consecuencias: [qué implicó]
```

[Repetir por cada ADR del proyecto]

---

## 6. Flujos de Datos Principales

### Flujo 1: [nombre del flujo]

```
[Usuario] → [Frontend] → [API] → [Base de Datos]
    1. Usuario realiza [acción]
    2. Frontend envía [request] a [endpoint]
    3. API valida [información]
    4. API consulta/actualiza [entidad]
    5. API retorna [response]
    6. Frontend muestra [resultado]
```

### Flujo 2: [nombre del flujo]

[mismo formato]

---

## 7. Consideraciones de Seguridad

- **Autenticación:** [mecanismo utilizado]
- **Autorización:** [roles y permisos implementados]
- **Encriptación en tránsito:** [TLS 1.3, etc.]
- **Encriptación en reposo:** [base de datos encriptada, etc.]
- **Validación de inputs:** [medidas implementadas]
- **Rate limiting:** [política aplicada]
- **OWASP:** [medidas contra top 10]

---

## 8. Estrategia de Escalabilidad

### Escalado actual

[Descripción de cómo escala el sistema actualmente]

### Cuellos de botella identificados

| Componente | Límite actual | Estrategia de escalado |
|-----------|--------------|----------------------|
| [componente] | [límite] | [estrategia] |

### Proyección de crecimiento

[Estimación de crecimiento y cuándo se necesitarían cambios arquitectónicos]

---

## 9. Referencias

- ADR #1 — [título] → [`decisions/ADR-1.md`]
- ADR #2 — [título] → [`decisions/ADR-2.md`]
- Project Context → [`context/project-context.md`]
- [Otra documentación relevante]
