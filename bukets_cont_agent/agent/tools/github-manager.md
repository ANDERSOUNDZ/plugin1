# GESTOR DE GITHUB — Integración con el flujo SDLC
> El agente usa esta guía para gestionar el proyecto en GitHub (issues, milestones, branches, PRs, releases).
> El agente NO tiene acceso directo a la API. Indica al usuario los comandos exactos a ejecutar.

---

## 📋 Prerrequisito

Antes de empezar, el usuario debe tener:
1. Un repositorio en GitHub
2. [GitHub CLI](https://cli.github.com/) instalado (`gh`)
3. Autenticación realizada (`gh auth login`)
4. El repo clonado localmente

---

## 🔗 Vinculación con el proyecto

```
Al inicio del proyecto (Fase 0), pregunta al usuario:
"¿Cuál es la URL de tu repositorio de GitHub?"
```

Actualiza project-context.md con:
```
GitHub:
  Repo URL: https://github.com/usuario/proyecto
  Milestone activo: [nombre]
  Issues abiertos: [N]
  PRs abiertos: [N]
```

---

## 🏷️ Gestión por fase

### Fase 0 — Entrevista
```
Acción: Verificar que el repo existe y está accesible.
Comando: gh repo view [usuario/repo]
Si no existe: guiar al usuario a crearlo en github.com
```

### Fase 1 — Requerimientos
Por cada user story aprobada, crear un issue:

```
Comando:
gh issue create \
  --title "feat: [nombre de la historia]" \
  --label "feature" \
  --project "[nombre del project board si existe]" \
  --milestone "[nombre del milestone]" \
  --body "$(cat .github/ISSUE_TEMPLATE/feature.md | sed 's/\[...\]/valor real/g')"

Labels por prioridad:
  Must have  → label: priority-high
  Should have → label: priority-medium
  Could have  → label: priority-low
  Won't have  → no se crea issue

Milestones:
  - Crear milestone "MVP" para historias del MVP
  - Crear milestone "Fase 2" para historias post-MVP
  Comando: gh api repos/:owner/:repo/milestones -f title="MVP" -f due_on="fecha"
```

### Fase 2 — Diseño
```
Acción: Crear milestones para las fases y asignar issues existentes.

Comando milestones:
gh api repos/:owner/:repo/milestones -f title="Fase 3 - Desarrollo" -f due_on="YYYY-MM-DD"
gh api repos/:owner/:repo/milestones -f title="Fase 4 - QA" -f due_on="YYYY-MM-DD"
gh api repos/:owner/:repo/milestones -f title="Fase 5 - Despliegue" -f due_on="YYYY-MM-DD"

Acción: Si se crea un ADR importante, crear un issue de documentación:
gh issue create --title "docs: [decisión]" --label "documentation" --body "[texto del ADR]"
```

### Fase 3 — Desarrollo
Por cada feature a desarrollar:

```
Paso 1: Crear branch desde el issue
Comando: gh issue develop [N] --name feat/[nombre-corto]
  (esto crea la branch y la cambia automáticamente)

O manualmente:
  git checkout -b feat/nombre-corto
  gh issue develop [N] --branch-name feat/nombre-corto

Paso 2: Desarrollar la feature y hacer commits
  git add .
  git commit -m "feat(scope): descripción (#N)"
  git push -u origin feat/nombre-corto

Paso 3: Crear Pull Request
Comando:
  gh pr create \
    --title "feat: [nombre]" \
    --body "$(cat .github/PULL_REQUEST_TEMPLATE.md)" \
    --label "feature" \
    --assignee "@me"

Paso 4: Solicitar code review
  gh pr comment [N] --body "Listo para review @[revisor]"
```

### Fase 4 — Testing / QA
```
Por cada PR:
  gh pr review [N] --approve (si pasa QA)
  gh pr merge [N] --merge --delete-branch
  gh issue close [N] --comment "Completado en PR #N. ✅ QA aprobado."

Si hay bugs encontrados en QA:
  gh issue create --label "bug" --title "fix: [descripción]" \
    --body "$(cat .github/ISSUE_TEMPLATE/bug.md)"
```

### Fase 5 — Despliegue
```
Acción: Crear GitHub Release

Paso 1: Listar cambios desde la última versión
  gh release list

Paso 2: Crear release
  gh release create v[N] \
    --title "v[N]" \
    --notes "Release notes generadas por SDLC Agent:
    - Features: [lista]
    - Fixes: [lista]
    - Cambios: [lista]" \
    --target main

Paso 3: Cerrar milestone completado
  gh api repos/:owner/:repo/milestones/[N] -X PATCH -f state=closed
```

### Fase 6 — Mantenimiento
```
Por cada bug reportado:
Paso 1: gh issue create --label "bug" con template
Paso 2: gh issue develop [N] --branch-name fix/descripcion
Paso 3: git add + commit + push
Paso 4: gh pr create con template de bug fix
Paso 5: gh pr merge cuando esté aprobado
Paso 6: gh issue close [N]

Por cada mejora técnica:
  gh issue create --label "task" con template
```

### Fase 7 — Documentación de Entrega
```
Acción: Verificar estado final del proyecto en GitHub

Revisar:
  gh issue list --milestone "MVP" --state open
    → Si hay issues abiertos, preguntar al usuario si deben cerrarse o moverse

  gh pr list --state open
    → Si hay PRs abiertos, preguntar al usuario si deben mergearse

  gh release list --limit 1
    → Confirmar que la última release coincide con la versión del proyecto

Cerrar milestone final:
  gh api repos/:owner/:repo/milestones/[N] -X PATCH -f state=closed
```

---

## 🔄 Sincronización en cada cambio

Cuando ocurre cualquier cambio en el proyecto, el gestor GitHub debe ejecutar:

```
1. IDENTIFICAR: ¿Este cambio está vinculado a un issue existente?
   - Si es nuevo → gh issue create
   - Si es existente → gh issue comment con actualización

2. EJECUTAR: Si el cambio implica código:
   - gh issue develop [N] (o crear branch manualmente)
   - git commit con referencia al issue (#N)
   - gh pr create

3. COMPLETAR: 
   - gh pr merge
   - gh issue close [N]
```

---

## 📊 Reporte de estado GitHub

```
📊 REPORTE GITHUB — [fecha]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Issues abiertos:     [N]
  - features:        [N]
  - bugs:            [N]
  - tasks:           [N]
PRs abiertos:        [N]
Milestones activos:  [N]
Última release:      [vX.X.X]
Branch actual:       [nombre]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```
