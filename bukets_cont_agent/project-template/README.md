# [Nombre del Proyecto]

> Proyecto gestionado con SDLC Agent Framework

## Empezar

1. Abre `../agente/agent/core/agent.md` y copia todo su contenido en ChatGPT o Claude como system prompt
2. Completa el archivo `context/project-context.md` con los datos de tu proyecto
3. Dile al agente: "hola, aquí está mi contexto: [pega el contenido de context/project-context.md]"
4. El agente comenzará la entrevista y te guiará paso a paso

## Estructura

```
mi-proyecto/
├── context/
│   └── project-context.md     ← Estado del proyecto (actualízalo siempre)
├── decisions/                  ← ADRs (decisiones arquitectónicas)
├── docs/
│   ├── delivery/               ← Documentación de entrega (la genera el agente)
│   ├── sprints/                ← Documentación de sprints (la genera el agente)
│   └── progress/               ← Reportes de avance (los genera el agente)
├── sessions/                   ← Notas de cada sesión
└── README.md
```
