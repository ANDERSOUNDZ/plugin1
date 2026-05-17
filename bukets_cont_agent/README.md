# SDLC Agent Framework

> Framework de desarrollo de software guiado por agente IA.
> Agnóstico de LLM. Aprende, revisa, piensa y mejora en cada iteración.

---

## ⭐ Archivo Maestro

**`agent/core/agent.md`** — Copia **todo el contenido** de este archivo como system prompt en cualquier LLM (Claude, ChatGPT, Gemini, etc.).

Sin este archivo el framework no funciona. Es el **corazón del sistema**.

---

## 🚀 Inicio rápido (3 pasos)

### Paso 1 — Abre el archivo maestro

Ve a `agent/core/agent.md` y **copia todo su contenido** (839 líneas).

### Paso 2 — Pégalo como system prompt

| Plataforma | Dónde pegarlo |
|------------|--------------|
| **Claude** (claude.ai) | Projects → Project Instructions |
| **ChatGPT** | Custom Instructions o GPT System Prompt |
| **Gemini** | Gems → Instrucciones del Gem |
| **Mastra / LangChain** | System message de tu agente |

### Paso 3 — Inicia el proyecto

Solo di **"hola"** o describe tu proyecto. El agente comenzará automáticamente la **entrevista inicial (Fase 0)** y te guiará paso a paso hasta la entrega final.

---

## ¿Qué es esto? (para cualquier persona)

Imagina que contratas a un **arquitecto de software senior con 20 años de experiencia** que se sienta a tu lado y te guía en cada paso, desde la idea hasta el producto final. Eso es SDLC Agent Framework.

No es código que se ejecuta solo. Es un **sistema de instrucciones** que le das a una IA (como ChatGPT o Claude) para que se comporte como ese arquitecto. La IA deja de ser un chatbot genérico y se convierte en un **director de proyecto, arquitecto, validador de calidad y documentador** todo en uno.

### ¿Qué hace exactamente?

| Paso | Qué hace el agente |
|------|-------------------|
| 1 | Te **entrevista** para entender tu problema antes de escribir código |
| 2 | Te ayuda a definir **qué construir** (requisitos) |
| 3 | Diseña la **arquitectura** del sistema |
| 4 | Escribe **código de calidad** contigo |
| 5 | **Prueba** que todo funcione correctamente |
| 6 | Te ayuda a **desplegar** el sistema |
| 7 | Genera la **documentación** para entregar al cliente |

Cada paso tiene un **control de calidad**: no puedes avanzar al siguiente si el anterior no está bien hecho.

## Estructura del proyecto

```
/
├── README.md                           # Este archivo
│
├── .github/                            # Integración con GitHub
│   ├── ISSUE_TEMPLATE/
│   │   ├── feature.md                  # Template para issues de features
│   │   ├── bug.md                      # Template para issues de bugs
│   │   └── task.md                     # Template para issues de tareas técnicas
│   └── PULL_REQUEST_TEMPLATE.md        # Template para pull requests
│
├── agent/                              # Núcleo del agente
│   ├── core/
│   │   └── agent.md                    # ⭐ ARCHIVO MAESTRO (system prompt)
│   ├── memory/
│   │   ├── project-context.md          # Plantilla de contexto del proyecto
│   │   └── config.md               # Configuración del framework (para el agente)
│   ├── loops/
│   │   ├── quality-loop.md             # Bucle de calidad universal
│   │   ├── change-loop.md              # Bucle para cambios y refactorizaciones
│   │   └── sync-loop.md                # Bucle de sincronización total
│   ├── phases/
│   │   └── phase-7-delivery.md         # Fase 7: Documentación de entrega
│   ├── tools/
│   │   ├── doc-generator.md            # Generador de documentación de entrega
│   │   ├── github-manager.md           # Gestor de GitHub (issues, PRs, releases)
│   │   ├── sprint-manager.md           # Gestor de sprints y planificación
│   │   └── doc-generator-commands.md   # Comandos rápidos de generación
│   └── validators/
│       ├── documentation-validator.md  # Validador de documentación
│       └── code-validator.md           # Validador de código
│
├── config/
│   └── framework.yml                   # Configuración global del framework
│
├── docs/
│   ├── guides/
│   │   ├── how-to-use.md               # Guía de uso del framework
│   │   ├── delivery-process.md         # Proceso de entrega de documentación
│   │   └── sprint-methodology-guide.md # Guía para elegir metodología de trabajo
│   ├── templates/
│   │   ├── adr-template.md             # Plantilla de ADR
│   │   ├── sprint/                     # Plantillas de sprints
│   │   │   ├── sprint-plan-template.md
│   │   │   ├── sprint-review-template.md
│   │   │   └── sprint-backlog-template.md
│   │   ├── progress/                   # Plantillas de avance
│   │   │   ├── status-report-template.md
│   │   │   ├── session-report-template.md
│   │   │   └── milestone-report-template.md
│   │   └── delivery/                   # Plantillas de documentos de entrega
│   │       ├── technical-architecture-template.md
│   │       ├── user-manual-template.md
│   │       ├── api-reference-template.md
│   │       ├── deployment-guide-template.md
│   │       ├── operations-guide-template.md
│   │       ├── release-notes-template.md
│   │       ├── admin-guide-template.md
│   │       └── security-compliance-template.md
│   └── architecture/
│
├── project-template/                    # Plantilla para nuevos proyectos (cópiala a otra carpeta)
│   ├── context/
│   │   └── project-context.md
│   ├── decisions/
│   │   └── adr-template.md
│   ├── docs/
│   │   ├── delivery/
│   │   ├── sprints/
│   │   └── progress/
│   ├── sessions/
│   └── README.md
│
├── examples/
│   └── example-project-context.md      # Ejemplo de contexto completado
│
├── scripts/
│   └── new-project.sh                  # Script para iniciar nuevo proyecto
│
└── tests/
    ├── unit/
    └── integration/
```

## Principios del framework

1. **Primero entender, luego construir** — nunca código sin contexto
2. **Bucle de calidad obligatorio** — cada fase se cierra o no avanza
3. **Aprendizaje continuo** — el agente aprende de cada error y decisión
4. **Agnóstico de tecnología** — funciona con cualquier stack
5. **Agnóstico de LLM** — funciona con cualquier modelo
6. **Documentación como ciudadano de primera clase** — no es opcional
7. **Documentación de entrega sincronizada siempre** — cada cambio en el proyecto actualiza automáticamente los documentos que se entregan al cliente

## 📁 Cómo usar (solo copiar y pegar)

Crea **dos carpetas separadas** en tu computadora:

```
📁 agente/              ← Copia todo el framework aquí (una sola vez)
📁 mi-proyecto/         ← Copia project-template/ aquí (un proyecto a la vez)
```

### Paso a paso

```
1. COPIA toda esta carpeta y pégala en una carpeta llamada "agente"

2. COPIA la carpeta "project-template/" y pégala en otra carpeta
   llamada "mi-proyecto" (o el nombre de tu proyecto)

3. En "mi-proyecto", completa context/project-context.md
   con los datos de tu proyecto

4. Abre "agente/agent/core/agent.md", copia todo su contenido (839 líneas)
   y pégalo como system prompt en ChatGPT o Claude

5. Dile al agente:
   "Hola, aquí está el contexto de mi proyecto: [pega el contenido
   de mi-proyecto/context/project-context.md]"

6. El agente comenzará automáticamente la entrevista (Fase 0)
   y te guiará hasta la entrega final
```

**El agente y el proyecto NUNCA se mezclan** porque están en carpetas separadas.

## Cómo contribuir

Para contribuir, revisa la estructura del proyecto y envía un pull request con tus mejoras.
