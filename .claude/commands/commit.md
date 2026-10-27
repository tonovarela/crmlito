---
description: Hace commit de los cambios, crea un tag de versión y lo sube a GitHub
argument-hint: "[mensaje del commit] [versión, ej. v1.2.0]"
allowed-tools: Bash(git status:*), Bash(git diff:*), Bash(git log:*), Bash(git tag:*), Bash(git add:*), Bash(git commit:*), Bash(git push:*), Bash(git rev-parse:*), Bash(git branch:*), AskUserQuestion
---

## Contexto

- Rama actual: !`git branch --show-current`
- Estado: !`git status --short`
- Resumen de cambios: !`git diff HEAD --stat`
- Últimos commits: !`git log --oneline -5`
- Último tag: !`git tag --sort=-v:refname | head -1`

Argumentos recibidos: $ARGUMENTS

## Tarea

1. Si no hay cambios (ni en el working tree ni en staging), avisa al usuario y termina.
2. **Pide los datos al usuario** (salvo que ya vengan en los argumentos):
   - **Comentarios del commit**: el mensaje que describe los cambios. Puedes proponer uno en español basado en el diff, pero el usuario decide.
   - **Versión del tag**: propón la siguiente versión semver a partir del último tag (patch, minor, major). Si no existe ningún tag, propón `v1.0.0`.
3. Normaliza la versión para que empiece con `v` (el workflow `docker-publish.yml` solo se dispara con tags `v*`). Verifica con `git tag -l <versión>` que el tag no exista; si existe, pide otra versión.
4. Muestra un resumen (archivos, mensaje, tag, rama) y ejecuta:
   ```bash
   git add -A
   git commit -m "<mensaje>"
   git tag -a <versión> -m "<mensaje>"
   git push origin <rama>
   git push origin <versión>
   ```
5. Si algún paso falla, detente y reporta el error sin intentar forzar (`--force`) nada.
6. Al terminar, confirma el hash del commit, el tag creado y que ambos se subieron a `origin`.
