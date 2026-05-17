# VALIDADOR DE CÓDIGO
> El agente usa esta guía al revisar cualquier código compartido.

---

## PROCESO DE ANÁLISIS DE CÓDIGO

El agente analiza el código en este orden estricto:

```
1. CORRECCIÓN    → ¿Hace lo que debe?
2. SEGURIDAD     → ¿Es seguro?
3. LEGIBILIDAD   → ¿Es fácil de entender?
4. DISEÑO        → ¿Aplica SOLID y patrones correctos?
5. PRUEBAS       → ¿Tiene cobertura adecuada?
6. RENDIMIENTO   → ¿Hay problemas obvios de performance?
```

---

## CHECKLIST DE REVISIÓN COMPLETO

### Corrección
- [ ] Cumple los criterios de aceptación de la user story
- [ ] Maneja el caso base correctamente
- [ ] Maneja casos límite (valores vacíos, nulos, extremos)
- [ ] Maneja errores y excepciones explícitamente
- [ ] No introduce regresiones en funcionalidad existente
- [ ] Los resultados son deterministas (mismo input → mismo output)

### Seguridad
- [ ] Inputs del usuario son validados y sanitizados
- [ ] Sin datos sensibles en logs o respuestas de error
- [ ] Sin credenciales o secrets hardcodeados
- [ ] Principio de mínimo privilegio aplicado
- [ ] Sin vulnerabilidades de injection obvias (SQL, XSS, etc.)
- [ ] Autenticación y autorización verificadas donde aplica

### Legibilidad (Clean Code)
- [ ] Nombres de variables describen su propósito (no x, tmp, data)
- [ ] Nombres de funciones describen QUÉ hacen, no CÓMO
- [ ] Nombres de clases son sustantivos, funciones son verbos
- [ ] Sin abreviaciones crípticas
- [ ] Sin comentarios que expliquen lo obvio
- [ ] Los comentarios que existen explican el POR QUÉ, no el QUÉ
- [ ] Sin código comentado (debe eliminarse)
- [ ] Sin código muerto (funciones que nunca se llaman)
- [ ] Funciones de máximo 20 líneas como guía
- [ ] Sin anidamiento mayor a 3 niveles (indicador de complejidad)

### Diseño (SOLID)
- [ ] S: Cada clase/módulo tiene una sola responsabilidad
- [ ] O: Se puede extender sin modificar código existente
- [ ] L: Las implementaciones son sustituibles por su interfaz
- [ ] I: Las interfaces son pequeñas y específicas
- [ ] D: Depende de abstracciones, no de implementaciones concretas
- [ ] Sin duplicación de lógica (DRY aplicado)
- [ ] Sin lógica de negocio en la capa de infraestructura
- [ ] Sin acceso directo a base de datos desde controladores

### Pruebas
- [ ] Existe al menos un test para el happy path
- [ ] Existen tests para los casos de error principales
- [ ] Los tests son independientes entre sí
- [ ] Los tests tienen nombres descriptivos
- [ ] No se prueban detalles de implementación
- [ ] Los mocks/stubs son apropiados y no sobre-especificados

### Rendimiento
- [ ] Sin queries N+1 en bases de datos
- [ ] Sin carga de datos innecesaria (solo lo que se necesita)
- [ ] Sin operaciones costosas dentro de loops
- [ ] Sin memory leaks obvios (listeners no removidos, etc.)

---

## FORMATO DE REPORTE DE CODE REVIEW

```
CODE REVIEW — [nombre del archivo / función / PR]
Fecha: [fecha]
Revisor: SDLC Agent
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✅ LO QUE ESTÁ BIEN:
- [punto positivo específico con línea o fragmento]
- [punto positivo 2]

🔴 CRÍTICO (bloquea el merge — resolver antes de continuar):
- Línea [N]: [descripción del problema]
  Problema: [qué está mal y por qué es crítico]
  Solución: [cómo corregirlo concretamente]

🟠 ALTO (resolver en este mismo ciclo):
- [problema con sugerencia de solución]

🟡 MEDIO (resolver en próximo sprint):
- [observación con recomendación]

🔵 SUGERENCIA (opcional, mejora la calidad):
- [idea de mejora con justificación]

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
VEREDICTO: [APROBADO / CAMBIOS REQUERIDOS / RECHAZADO]

Si CAMBIOS REQUERIDOS:
  Bloqueantes: [N] críticos, [N] altos
  Pasos siguientes: [qué hacer primero]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

## ANTIPATRONES COMUNES A DETECTAR

El agente alerta inmediatamente si detecta:

### Código
- **God Object/Class**: clase que lo hace todo
- **Magic Numbers**: números literales sin nombre (`if (status === 3)`)
- **Feature Envy**: una clase usa más métodos de otra que de sí misma
- **Shotgun Surgery**: un cambio requiere tocar muchos archivos
- **Primitive Obsession**: usar strings/ints donde deberían usarse objetos
- **Long Parameter List**: funciones con más de 4-5 parámetros
- **Nested Callbacks**: callback hell o promise hell

### Arquitectura
- **Circular Dependencies**: A depende de B, B depende de A
- **Leaky Abstraction**: detalles de implementación se filtran hacia arriba
- **Distributed Monolith**: microservicios que están fuertemente acoplados
- **Anemic Domain Model**: objetos de dominio sin comportamiento

### Testing
- **Test sin assertion**: un test que nunca puede fallar
- **Test acoplado a implementación**: el test sabe demasiado de cómo funciona
- **Fixtures compartidos**: tests que dependen del orden de ejecución
