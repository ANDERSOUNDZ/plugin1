# GENERADOR DE DOCUMENTACIÓN DE ENTREGA
> El agente usa esta herramienta para generar automáticamente cada documento de entrega
> a partir del contexto del proyecto, ADRs, user stories, código y configuración.

---

## ¿Qué hace esta herramienta?

Convierte la información estructurada del proyecto (project-context.md, ADRs, user stories, código, configuración) en **documentos de entrega listos para el cliente**, sin intervención manual.

---

## Reglas de generación

1. **Nunca inventar** — toda información debe tener una fuente en el proyecto
2. **Idioma del documento** = idioma del proyecto (configurado en framework.yml)
3. **Tono profesional pero claro** — ni demasiado técnico ni demasiado simple, según la audiencia
4. **Sin placeholders al finalizar** — si no hay información para una sección y es obligatoria, preguntar al usuario
5. **Sincronizado siempre** — antes de generar, leer el estado actual del proyecto-context.md

---

## Documentos que genera

### 1. Documento de Arquitectura Técnica

**Fuentes:** ADRs, project-context.md (stack tecnológico, decisiones), diseño de Fase 2

**Estructura que genera automáticamente:**
```
1. Resumen ejecutivo de la arquitectura
2. Diagrama de componentes (descripción textual de capas y comunicación)
3. Stack tecnológico detallado (con justificación de cada tecnología)
4. Modelo de datos (entidades principales, relaciones)
5. Decisiones arquitectónicas (ADRs compiladas en orden cronológico)
6. Flujos de datos principales (diagramas textuales)
7. Consideraciones de seguridad integradas en la arquitectura
8. Estrategia de escalabilidad
```

**Comportamiento del agente:**
```
Al generar este documento:
1. Leer todas las ADRs del proyecto
2. Extraer stack tecnológico de project-context.md
3. Revisar el modelo de datos de Fase 2
4. Compilar flujos de datos desde user stories y diseño
5. Generar documento estructurado con referencias cruzadas a ADRs
```

---

### 2. Manual de Usuario

**Fuentes:** User stories (Fase 1), criterios de aceptación, UI/UX definida, features implementadas

**Estructura que genera automáticamente:**
```
1. Introducción y propósito del sistema
2. Requisitos del sistema (navegador, dispositivo, acceso)
3. Guía de inicio rápido (primeros pasos en 5 pasos)
4. Funcionalidades cubiertas (una sección por user story del MVP)
5. Preguntas frecuentes (FAQ)
6. Solución de problemas comunes
7. Glosario de términos
```

**Comportamiento del agente:**
```
Al generar este documento:
1. Leer todas las user stories completadas de project-context.md
2. Extraer descripción, acción y beneficio de cada historia
3. Generar instrucciones paso a paso para cada funcionalidad
4. Incluir capturas de pantalla descriptivas (textuales) de cada flujo
5. Generar FAQ basado en errores comunes y casos límite de las historias
```

---

### 3. API Reference

**Fuentes:** Contratos de API (Fase 2), código de endpoints (Fase 3)

**Estructura que genera automáticamente:**
```
1. Introducción y base URL
2. Autenticación y autorización
3. Endpoints (uno por recurso):
   - Método HTTP y ruta
   - Descripción de qué hace
   - Parámetros (path, query, body)
   - Ejemplo de request
   - Ejemplo de response (200, 4xx, 5xx)
   - Códigos de error posibles
4. Modelos de datos (schemas request/response)
5. Rate limiting y políticas de uso
6. Ejemplos de flujos completos
```

**Comportamiento del agente:**
```
Al generar este documento:
1. Extraer contratos de API del diseño de Fase 2
2. Revisar implementación real en el código (Fase 3)
3. Documentar cada endpoint con request/response reales
4. Incluir ejemplos basados en los casos de prueba de Fase 4
```

---

### 4. Guía de Despliegue

**Fuentes:** Configuración de infraestructura (Fase 5), CI/CD pipeline, configuración de entorno

**Estructura que genera automáticamente:**
```
1. Requisitos de infraestructura (hardware/cloud mínimo)
2. Dependencias externas (base de datos, servicios, APIs)
3. Variables de entorno requeridas (con descripción de cada una)
4. Paso a paso del despliegue inicial
5. Pipeline CI/CD (cómo funciona, cómo se activa)
6. Estrategia de despliegue (blue-green, rolling, etc.)
7. Plan de rollback (procedimiento paso a paso)
8. Verificación post-despliegue (smoke tests)
```

**Comportamiento del agente:**
```
Al generar este documento:
1. Extraer configuración de infraestructura de Fase 5
2. Revisar pipeline CI/CD real del proyecto
3. Documentar variables de entorno de producción
4. Incluir plan de rollback documentado en Fase 5
```

---

### 5. Guía de Operaciones

**Fuentes:** Plan de monitoreo (Fase 5-6), configuración de alertas, logs, backups

**Estructura que genera automáticamente:**
```
1. Dashboard de monitoreo (qué métricas observar)
2. Alertas configuradas (qué dispara cada alerta, qué hacer)
3. Gestión de logs (dónde están, cómo consultarlos, retención)
4. Procedimientos operativos del día a día
5. Plan de backup y recuperación
6. Runbook de incidentes comunes:
   - Servicio caído
   - Base de datos lenta
   - Error de autenticación masivo
   - Alta carga inesperada
7. Escalamiento (a quién llamar según el incidente)
```

**Comportamiento del agente:**
```
Al generar este documento:
1. Extraer configuración de monitoreo de Fase 5-6
2. Revisar alertas configuradas en el proyecto
3. Documentar procedimientos de backup
4. Generar runbook basado en errores aprendidos durante el proyecto
```

---

### 6. Release Notes

**Fuentes:** Historial de cambios del proyecto, commits, ADRs, errores aprendidos

**Estructura que genera automáticamente:**
```
Versión [N] — [fecha]
┌─────────────────────────────────────────┐
│ Nuevas funcionalidades:                  │
│ - [feature 1] (Historia #N)             │
│ - [feature 2] (Historia #N)             │
│                                          │
│ Correcciones:                            │
│ - [bug corregido 1] (Error #N)          │
│ - [bug corregido 2] (Error #N)          │
│                                          │
│ Mejoras técnicas:                        │
│ - [refactor/optimización 1] (ADR #N)    │
│                                          │
│ Cambios de configuración:                │
│ - [variable de entorno nueva/cambiada]    │
│                                          │
│ Actualizaciones de dependencias:         │
│ - [dependencia] → [versión anterior] → [nueva versión]│
│                                          │
│ Breaking changes:                        │
│ - [cambio que requiere acción del cliente]│
└─────────────────────────────────────────┘
```

**Comportamiento del agente:**
```
Al generar este documento:
1. Revisar todas las user stories marcadas como completadas
2. Revisar errores aprendidos con solución aplicada
3. Revisar ADRs y cambios en la configuración
4. Si hay tags de git, revisar commits entre versiones
```

---

### 7. Guía de Administración

**Fuentes:** Configuración de usuarios, roles, permisos, backups, settings del sistema

**Estructura que genera automáticamente:**
```
1. Gestión de usuarios y roles
2. Configuración del sistema (settings editables)
3. Mantenimiento programado (ventanas, procedimientos)
4. Backup y restore (procedimiento detallado)
5. Gestión de logs (consulta, retención, exportación)
6. Facturación y límites (si aplica)
7. Troubleshooting administrativo
```

---

### 8. Documentación de Seguridad y Compliance

**Fuentes:** Decisiones de seguridad en ADRs, reqs no funcionales de Fase 1, configuración de seguridad

**Estructura que genera automáticamente:**
```
1. Políticas de seguridad implementadas
2. Autenticación y autorización (mecanismos, flujos)
3. Protección de datos personales (GDPR, CCPA, etc.)
4. Encriptación (en tránsito y en reposo)
5. Gestión de vulnerabilidades (parches, escaneo)
6. Plan de respuesta a incidentes de seguridad
7. Compliance (estándares que cumple el sistema)
8. Contacto de seguridad
```

---

## Integración con el validador

Cada documento generado debe pasar por `documentation-validator.md` antes de darse por completo. El generador marca el documento como listo para validación una vez que todas las secciones tienen contenido.

---

## Cuándo se ejecuta automáticamente

| Evento | Acción del generador |
|--------|---------------------|
| Cierre de Fase 1 | Generar/actualizar borrador del Manual de Usuario |
| Cierre de Fase 2 | Generar/actualizar Documento de Arquitectura + API Reference |
| Cierre de Fase 3 | Actualizar API Reference con endpoints reales |
| Cierre de Fase 4 | Agregar secciones de calidad/pruebas a todos los docs |
| Cierre de Fase 5 | Generar/actualizar Guía de Despliegue + Guía de Operaciones |
| Cierre de Fase 6 | Generar Release Notes, actualizar docs por cambios |
| Cierre de sesión | Verificar que los docs afectados estén sincronizados |
| Cualquier cambio | Actualizar el documento específico afectado por el cambio |
