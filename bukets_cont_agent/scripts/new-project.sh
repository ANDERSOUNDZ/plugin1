#!/bin/bash
# new-project.sh
# Inicializa la estructura de un nuevo proyecto con el SDLC Agent Framework

set -e

# Colores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${BLUE}"
echo "╔══════════════════════════════════════════╗"
echo "║        SDLC Agent Framework              ║"
echo "║        Nuevo Proyecto                    ║"
echo "╚══════════════════════════════════════════╝"
echo -e "${NC}"

# Pedir nombre del proyecto
read -p "Nombre del proyecto: " PROJECT_NAME

if [ -z "$PROJECT_NAME" ]; then
  echo "❌ El nombre del proyecto no puede estar vacío"
  exit 1
fi

# Crear slug del nombre (minúsculas, guiones)
PROJECT_SLUG=$(echo "$PROJECT_NAME" | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -cd '[:alnum:]-')
PROJECT_DIR="projects/$PROJECT_SLUG"

echo ""
echo -e "${YELLOW}Creando estructura para: $PROJECT_NAME${NC}"
echo ""

# Crear estructura del proyecto
mkdir -p "$PROJECT_DIR"/{context,decisions,docs/delivery,sessions}

# Copiar plantilla de contexto
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
FRAMEWORK_DIR="$(dirname "$SCRIPT_DIR")"

cp "$FRAMEWORK_DIR/agent/memory/project-context.md" "$PROJECT_DIR/context/project-context.md"
cp "$FRAMEWORK_DIR/docs/templates/adr-template.md" "$PROJECT_DIR/decisions/adr-template.md"

# Copiar plantillas de documentación de entrega
cp "$FRAMEWORK_DIR/docs/templates/delivery/"*.md "$PROJECT_DIR/docs/delivery/"

# Reemplazar nombre del proyecto en el contexto
sed -i "s/\[Nombre del Proyecto\]/$PROJECT_NAME/g" "$PROJECT_DIR/context/project-context.md"
sed -i "s/\[fecha\]/$(date '+%Y-%m-%d')/g" "$PROJECT_DIR/context/project-context.md"

# Crear README del proyecto
cat > "$PROJECT_DIR/README.md" << EOF
# $PROJECT_NAME

> Proyecto gestionado con SDLC Agent Framework

## Estado del proyecto

Fase actual: 0 — Entrevista inicial

## Cómo continuar con el agente

1. Abre tu LLM preferido con el system prompt de \`agent/core/agent.md\`
2. Comparte el contenido de \`context/project-context.md\` al inicio de cada sesión
3. El agente retomará desde donde lo dejaste

## Estructura

\`\`\`
$PROJECT_SLUG/
├── context/
│   └── project-context.md    ← Memoria viva del proyecto
├── decisions/
│   └── adr-template.md       ← Plantilla para decisiones
├── docs/
│   └── delivery/             ← Documentación de entrega
├── sessions/                 ← Notas de cada sesión
└── README.md
\`\`\`
EOF

# Crear archivo de primera sesión
SESSION_DATE=$(date '+%Y-%m-%d')
cat > "$PROJECT_DIR/sessions/session-$SESSION_DATE.md" << EOF
# Sesión — $SESSION_DATE

## Estado al iniciar
Fase: 0 — Entrevista inicial (sin comenzar)

## Qué se trabajó
[completar durante la sesión]

## Decisiones tomadas
[completar durante la sesión]

## Próxima sesión
[completar al cerrar]
EOF

echo -e "${GREEN}"
echo "✅ Proyecto creado exitosamente en: $PROJECT_DIR"
echo ""
echo "Próximos pasos:"
echo "  1. Copia agent/core/agent.md como system prompt en tu LLM"
echo "  2. Comparte $PROJECT_DIR/context/project-context.md al iniciar"
echo "  3. El agente iniciará la entrevista automáticamente"
echo -e "${NC}"
