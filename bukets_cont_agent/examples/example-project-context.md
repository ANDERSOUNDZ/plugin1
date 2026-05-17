# CONTEXTO DEL PROYECTO — Sistema de Gestión de Inventario
> Ejemplo de cómo se ve un contexto completado al final de la Fase 1.

---

## ESTADO ACTUAL

```
Fecha de inicio:        2026-05-01
Última actualización:   2026-05-10
Fase actual:            2 — Diseño y Arquitectura
Subfase:                Definiendo arquitectura del sistema
Bucle de calidad:       ABIERTO (pendiente aprobación de arquitectura)
Próxima acción:         Presentar ADR #1 de arquitectura al equipo
```

---

## RESUMEN DEL PROYECTO

```
Nombre:             InventaApp
Problema central:   Las tiendas pequeñas gestionan inventario en Excel,
                    generando desincronización entre vendedores y pérdidas
                    por falta de stock o exceso de compra.
Usuarios objetivo:  Dueños y empleados de tiendas de retail con 1-5 empleados
MVP definido:       Registro de productos, control de stock en tiempo real,
                    alertas de stock bajo, historial de movimientos básico
Fuera del MVP:      Facturación, integración contable, app móvil nativa,
                    múltiples sucursales
Modelo de trabajo:  Scrum con sprints de 2 semanas
```

---

## STACK TECNOLÓGICO ACORDADO

```
Frontend:       React 18 + TypeScript — equipo con experiencia previa
Backend:        Node.js + Express + TypeScript — mismo lenguaje en todo el stack
Base de datos:  PostgreSQL — datos relacionales, transacciones importantes
Infraestructura: Railway (deploy simple para MVP, migrable)
CI/CD:          GitHub Actions
Testing:        Vitest (unit) + Playwright (E2E)
Monitoreo:      Sentry (errores) + Railway métricas básicas
```

---

## DECISIONES TOMADAS

### Decisión #1
```
Fecha: 2026-05-03
Fase: 1 — Requerimientos
Decisión: El MVP no incluirá app móvil nativa, solo web responsiva
Alternativas descartadas:
  - React Native: aumenta complejidad y tiempo sin validar si el usuario
    prefiere móvil sobre web
Justificación: Primero validar el producto con web. Si hay adopción,
               se evalúa móvil en la siguiente fase.
Impacto: El frontend debe ser 100% responsivo desde el día 1
Revisable cuando: Al cierre del MVP si más del 60% del uso es móvil
```

### Decisión #2
```
Fecha: 2026-05-05
Fase: 1 — Requerimientos
Decisión: La alerta de stock bajo es automática con umbral configurable por producto
Alternativas descartadas:
  - Umbral global: no sirve, una tienda necesita diferente umbral para
    leche (umbral 10) que para TV (umbral 1)
Justificación: Cada producto tiene su propia rotación y criticidad
Impacto: El modelo de datos debe incluir "stock_minimo" por producto
Revisable cuando: Nunca — es un requerimiento del usuario
```

---

## ERRORES APRENDIDOS

### Error #1
```
Fecha: 2026-05-07
Fase: 1 — Requerimientos
Error: Se definió "gestión de proveedores" como parte del MVP sin
       validar si los usuarios realmente lo necesitan ahora
Causa raíz: Asumimos que si gestionas inventario, necesitas gestionar
            proveedores. No preguntamos al usuario.
Solución aplicada: Entrevista adicional con 3 usuarios. El 100% dijo
                   que puede seguir contactando proveedores por WhatsApp
                   por ahora. Se movió a fase 2.
Prevención futura: SIEMPRE validar cada feature con usuarios reales antes
                   de incluirla. La intuición del equipo no es suficiente.
```

---

## REQUERIMIENTOS

### Historia #1: Registrar producto
```
Como empleado de tienda
Quiero registrar un nuevo producto con nombre, SKU, precio y stock inicial
Para tener el inventario digital desde el primer día

Criterios de aceptación:
- [ ] Dado que soy empleado logueado, cuando completo el formulario con
      datos válidos, entonces el producto aparece en el listado inmediatamente
- [ ] Dado que ingreso un SKU duplicado, entonces el sistema me avisa sin
      perder los datos ingresados
- [ ] Dado que dejo campos obligatorios vacíos, entonces el sistema marca
      los campos faltantes específicamente

Estimación: M
Prioridad: Must have
Estado: Completado ✅
```

### Historia #2: Ver stock actual
```
Como dueño o empleado
Quiero ver el stock actual de todos los productos en una sola pantalla
Para saber qué tengo y qué me falta sin buscar en Excel

Criterios de aceptación:
- [ ] Dado que estoy en el listado, veo nombre, SKU, stock actual y
      stock mínimo de cada producto
- [ ] Dado que el stock está por debajo del mínimo, el producto se
      destaca visualmente en rojo
- [ ] Dado que hay más de 50 productos, puedo buscar y filtrar

Estimación: M
Prioridad: Must have
Estado: En progreso
```

---

## RIESGOS

| # | Riesgo | Probabilidad | Impacto | Mitigación | Estado |
|---|--------|-------------|---------|------------|--------|
| 1 | El usuario no adopta la herramienta por costumbre al Excel | Alta | Alto | Onboarding personalizado + importación de Excel en MVP | Activo |
| 2 | Railway tiene downtime afectando al usuario | Baja | Alto | Plan de migración a Fly.io documentado | Activo |
| 3 | El equipo subestimó la complejidad del control de stock | Media | Medio | PoC del flujo de movimientos antes de comprometer fecha | Resuelto |

---

## PATRONES DESCUBIERTOS

### Patrón #1
```
Contexto: Validación de features con usuarios antes de incluirlas en el MVP
Patrón: Entrevista rápida con 3 usuarios target antes de comprometer una feature
Beneficio: Evita construir funcionalidades que nadie usará
Aplicación: Antes de agregar cualquier feature al backlog, validar con al menos 3 usuarios
```

---

## DEUDA TÉCNICA

| # | Descripción | Tipo | Impacto | Esfuerzo | Prioridad | Estado |
|---|-------------|------|---------|----------|-----------|--------|
| 1 | No hay pruebas automatizadas en frontend | Pruebas | Medio | 2 días | Media | Pendiente |

---

## DOCUMENTACIÓN DE ENTREGA

### Estado de documentos

| Documento | Estado | Última actualización | Validado |
|-----------|--------|---------------------|----------|
| Arquitectura Técnica | Borrador | 2026-05-10 | No |
| Manual de Usuario | Borrador | 2026-05-08 | No |
| API Reference | No iniciado | - | - |
| Guía de Despliegue | No iniciado | - | - |
| Guía de Operaciones | No iniciado | - | - |
| Release Notes | No iniciado | - | - |
| Guía de Administración | No iniciado | - | - |
| Seguridad y Compliance | No iniciado | - | - |

---

## MÉTRICAS DE CALIDAD

| Métrica | Objetivo | Actual | Estado |
|---------|----------|--------|--------|
| Cobertura de pruebas | ≥ 70% | 0% (fase 2) | 🔵 No aplica aún |
| Bugs críticos abiertos | 0 | 0 | 🟢 OK |
| ADRs documentados | ≥ 1 por decisión | 2 | 🟢 OK |
| Deuda técnica | Baja | Baja | 🟢 OK |
| User stories completadas | 5/12 | 2/5 MVP | 🟡 En progreso |
| Documentos de entrega completos | 8 | 0 | 🔴 Pendiente |
| Docs sincronizados | 8 | 0 | 🔴 Pendiente |

---

## LOG DE SESIONES

### Sesión 2026-05-01
```
Duración: 2 horas
Fase trabajada: 0 — Entrevista inicial
Qué se hizo: Entrevista completa, resumen aprobado por el cliente
Decisiones tomadas: Ninguna (solo contexto)
Próxima sesión: Iniciar Fase 1 — user stories del MVP
```

### Sesión 2026-05-07
```
Duración: 3 horas
Fase trabajada: 1 — Requerimientos
Qué se hizo: 5 user stories del MVP documentadas, 3 riesgos identificados
Decisiones tomadas: Decisión #1 (sin app móvil en MVP), Decisión #2 (umbral por producto)
Error aprendido: Error #1 (gestión de proveedores movida fuera del MVP)
Próxima sesión: Completar user stories restantes y cerrar bucle de calidad Fase 1
```
