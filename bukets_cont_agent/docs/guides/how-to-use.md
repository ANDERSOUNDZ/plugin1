# Guía de uso del SDLC Agent Framework

## Para quién es esta guía

Para cualquier desarrollador, líder técnico o equipo que quiera usar el framework en un proyecto real.

---

## Inicio rápido (5 minutos)

### Paso 1 — Copia el system prompt

Abre `../../agent/core/agent.md` y copia todo su contenido.

### Paso 2 — Activa el agente en tu LLM preferido

**En Claude (claude.ai):**
1. Crea un nuevo Proyecto
2. Ve a "Project Instructions"
3. Pega el contenido de `../../agent/core/agent.md`
4. Empieza una conversación normal

**En ChatGPT:**
1. Crea un nuevo GPT personalizado, o
2. Usa la API con el contenido como `system` message

**En Gemini:**
1. Crea un nuevo Gem
2. Pega el contenido en las instrucciones del Gem

**En tu propia app (Mastra, LangChain, LlamaIndex, etc.):**
```typescript
// Ejemplo con Mastra
const agent = new Agent({
  name: "SDLC Agent",
  instructions: fs.readFileSync('../../agent/core/agent.md', 'utf-8'),
  model: anthropic("claude-sonnet-4-5"),
});
```

### Paso 3 — Inicia el proyecto

El agente comenzará automáticamente con la entrevista. Solo di hola o describe tu proyecto.

### Paso 4 — Mantén el contexto vivo

Copia `../../agent/memory/project-context.md`, renómbralo con el nombre de tu proyecto, y actualízalo al cierre de cada sesión.

Al inicio de cada sesión nueva, comparte el contexto con el agente:
> "Aquí está el contexto de nuestro proyecto: [pega el contenido]"

---

## Flujo de una sesión típica

```
1. Compartir contexto del proyecto (si es sesión nueva)
2. El agente revisa el estado actual y la fase en curso
3. Trabajar en la fase o subfase activa
4. Al cierre, el agente ejecuta el bucle de calidad
5. Si el bucle está cerrado, se avanza
6. Actualizar el archivo de contexto
```

---

## Cómo manejar múltiples proyectos

Crea una carpeta por proyecto:
```
projects/
├── proyecto-alpha/
│   ├── context/
│   │   └── project-context.md   ← Copia de la plantilla completada
│   └── decisions/               ← ADRs de este proyecto
├── proyecto-beta/
│   ├── context/
│   │   └── project-context.md
│   └── decisions/
```

Al iniciar sesión en un proyecto, comparte el `context/project-context.md` correspondiente.

---

## Cómo personalizar el framework

### Ajustar umbrales de calidad
Edita `../../config/framework.yml`:
```yaml
quality:
  test_coverage_minimum: 80   # Sube el estándar
  max_function_lines: 15      # Más estricto con tamaño de funciones
```

### Deshabilitar fases que no aplican
```yaml
phases:
  phase_5_deployment: false   # Si el proyecto no tiene deploy propio
```

### Ajustar el idioma
```yaml
framework:
  language: "en"  # Cambia a inglés
```

---

## Mejores prácticas

**Sé honesto con el agente.** Comparte los problemas reales, no solo lo que salió bien. El agente aprende de los errores y no puede ayudarte si no conoce la situación completa.

**No omitas la Fase 0.** La entrevista inicial parece tiempo perdido pero evita semanas de desarrollo en la dirección equivocada.

**Actualiza el contexto regularmente.** El archivo `context/project-context.md` es la memoria del agente. Si no lo actualizas, el agente pierde el hilo entre sesiones.

**Respeta el bloqueo del bucle.** Si el agente dice que no puede avanzar, no lo ignores. Los ítems bloqueantes siempre tienen razón de ser.

**Documenta las ADRs en el momento.** Las decisiones son fáciles de documentar cuando se toman. Reconstruirlas después es costoso y nunca queda igual.

---

## Solución de problemas comunes

**El agente no recuerda decisiones anteriores:**
→ Comparte el `context/project-context.md` al inicio de la sesión. Los LLMs no tienen memoria persistente entre conversaciones.

**El agente está siendo demasiado estricto:**
→ Es correcto que sea estricto. Si crees que un criterio no aplica a tu proyecto, discútelo con el agente — puede ajustar el enfoque con justificación.

**No sé en qué fase estoy:**
→ Consulta la tabla "Estado de Fases" en tu `context/project-context.md`. Si no está actualizada, pregunta al agente.

**El agente generó código sin entrevista:**
→ Esto no debería pasar si el system prompt está correctamente configurado. Verifica que el contenido completo de `../../agent/core/agent.md` esté en las instrucciones.
