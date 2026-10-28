# Despliegue con self-hosted runner en CentOS 7

## Qué es un self-hosted runner
Un self-hosted runner es el agente de GitHub Actions (`actions-runner`) instalado en **nuestro** servidor. El agente abre una conexión **saliente** HTTPS hacia GitHub y pregunta cada cierto tiempo si hay un job para él. Por eso no hace falta abrir puertos de entrada, configurar SSH ni tener IP pública.

- Cuando un job tiene `runs-on: [self-hosted, crmlito]`, GitHub se lo entrega al servidor. En este servidor el runner vive **dentro de un contenedor** (ver [Instalación](#instalación-en-centos-7-una-sola-vez)): cada `run:` se ejecuta como comando de shell en ese contenedor, y los comandos `docker` / `docker compose` llegan al Docker del CentOS a través del socket.
- `ubuntu-latest` es una máquina virtual temporal de GitHub que se borra al terminar el job. Sirve para construir la imagen, pero no llega a la red interna (SQL Server ni el puerto 8091).

Por eso el build se hace en la nube de GitHub y el deploy en el servidor:

```
git push del tag v1.0.10
   │
   ▼
[nube GitHub] build-and-push (ubuntu-latest)  → publica ghcr.io/tonovarela/crmlito:1.0.10
   │ needs
   ▼
[CentOS 7]    deploy (runner en contenedor)   → docker compose pull + up -d en /home/docker/crmlito
```

**Estado actual:**
- El build ya funciona: `.github/workflows/docker-publish.yml` publica la imagen en GHCR con cada tag `v*`.
- `.github/workflows/self-hosted-test.yml` es un workflow de prueba que se lanza a mano.
- Docker 26.1.2 y Compose v2.27.0 ya están en el servidor.
- Falta levantar el contenedor del runner (pasos 1 a 4) y agregar el job `deploy`.

## ¿Un runner por proyecto?
Depende del nivel en el que se registre:

| Nivel | Quién lo puede usar |
|---|---|
| **Repositorio** (Settings del repo) | Solo ese repo |
| **Organización** (Settings de la org) | Todos los repos de la org, o los que se elijan |
| **Enterprise** | Todas las organizaciones de la empresa |

`tonovarela` es una **cuenta personal**, y las cuentas personales no tienen runners compartidos. El runner que se registre en **crmlito → Settings → Runners** solo atiende a crmlito.

Para desplegar más proyectos en el mismo servidor hay dos opciones:
1. **Un contenedor de runner por repo en el mismo servidor**, cada uno con su `REPO_URL`, `RUNNER_NAME` y volumen de datos (por ejemplo, un servicio `runner-crmlito` y otro `runner-otro` en el mismo compose). Consumen muy poco mientras esperan trabajo.
2. **Crear una organización gratuita** y mover ahí los repos. Así basta con **un runner** registrado a nivel organización.

Un runner ejecuta **un job a la vez**. Si dos despliegues coinciden, el segundo espera en la cola.

## Instalación en CentOS 7 (una sola vez)

### Por qué el runner va dentro de un contenedor
El servidor tiene **CentOS Linux 7.9.2009**, que trae glibc 2.17. Las acciones de GitHub (`actions/checkout@v4` y similares) corren en Node 20/24, que necesita glibc 2.28 o más, y GitHub ya no soporta CentOS 7 para el runner. Además, CentOS 7 está fuera de soporte desde junio de 2024.

La solución es correr el runner **dentro de un contenedor** basado en Ubuntu, que trae su propia glibc. El contenedor usa el Docker del servidor a través de `/var/run/docker.sock`, así que el `docker compose up` levanta la app en el CentOS.

Versiones del servidor (revisadas):
- Docker Engine 26.1.2 (API 1.45)
- Docker Compose v2.27.0, el plugin `docker compose` con espacio, no con guion

A largo plazo conviene migrar el servidor a Rocky o AlmaLinux 8/9. Con eso el runner se puede instalar directamente, sin contenedor.

### 1. Obtener el token en GitHub
**github.com/tonovarela/crmlito → Settings → Actions → Runners → New self-hosted runner.** Solo se necesita el **token** que aparece en el comando `./config.sh --token ...`. Ese token **vence en 1 hora** y solo sirve para el primer registro.

### 2. Compose del runner
Se crea `/home/docker/actions-runner/docker-compose.yml`:

```yaml
services:
  runner:
    # Imagen de la comunidad (https://github.com/myoung34/docker-github-actions-runner).
    # Fijar una versión concreta, no latest.
    image: myoung34/github-runner:<versión>
    restart: unless-stopped
    environment:
      REPO_URL: https://github.com/tonovarela/crmlito
      RUNNER_TOKEN: ${RUNNER_TOKEN}
      RUNNER_NAME: centos-crm
      LABELS: crmlito
      RUNNER_WORKDIR: /tmp/runner/work
      # Guarda el registro en un volumen para que, al reiniciar, no pida otra vez el token
      CONFIGURED_ACTIONS_RUNNER_FILES_DIR: /runner/data
      DISABLE_AUTOMATIC_DEREGISTRATION: "true"
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
      # Misma ruta que en el servidor, para que el compose del CRM encuentre su .env
      - /home/docker/crmlito:/home/docker/crmlito
      - runner-data:/runner/data

volumes:
  runner-data:
```
Antes de usar las variables `CONFIGURED_ACTIONS_RUNNER_FILES_DIR` y `DISABLE_AUTOMATIC_DEREGISTRATION`, confirmar en el README de la versión elegida que existen.

### 3. Levantarlo
```bash
cd /home/docker/actions-runner
RUNNER_TOKEN=<TOKEN> docker compose up -d
docker compose logs -f runner        # debe decir "Listening for Jobs"
docker compose exec runner docker compose version
```
El último comando confirma que, desde dentro del contenedor, el runner puede usar el Docker del servidor. Con `restart: unless-stopped`, el contenedor vuelve a arrancar solo cuando se reinicia el servidor.

### 4. Verificar (en GitHub)
1. En **Settings → Actions → Runners**, `centos-crm` debe aparecer como **Idle** (punto verde). Si aparece **Offline**, revisar `docker compose logs runner` en el servidor.
2. En **Actions → "Prueba self-hosted runner" → Run workflow**, el log del paso "Info del equipo" debe mostrar el nombre `centos-crm`. `uname -a` muestra el kernel 3.10 de CentOS, porque el contenedor lo comparte, aunque el sistema de archivos sea Ubuntu. Agregar temporalmente `docker ps` al workflow para confirmar que se ven los contenedores del servidor.

En la red del servidor solo se necesita salida HTTPS (puerto 443) hacia github.com.

## Siguiente paso: job `deploy` (pendiente)
Agregar a `docker-publish.yml` un job que corra después del build:

- `needs: build-and-push`, `runs-on: [self-hosted, crmlito]` (la etiqueta del runner), `permissions: packages: read`
- solo pasos `run:`, sin `actions/checkout`: el compose ya vive en el servidor
- `concurrency` para que dos despliegues no corran al mismo tiempo
- `docker login ghcr.io` con `GITHUB_TOKEN`
- `cd /home/docker/crmlito` (ahí ya están el docker compose y el `.env` del CRM; **no** se copia el compose del repo para no pisar el del servidor)
- `IMAGE_TAG=${GITHUB_REF_NAME#v}` y después `docker compose pull && docker compose up -d`
- comprobar que la app responda con `docker compose exec -T app curl -fsS -o /dev/null http://localhost/`. Dentro del contenedor del runner, `localhost:8091` **no** apunta al servidor; la imagen de la app ya trae `curl`. Si no responde, mostrar `docker compose logs app` y marcar el job como fallido.
- `docker logout` y `docker image prune -f`

**Requisito:** el compose del servidor debe usar `image: ghcr.io/tonovarela/crmlito:${IMAGE_TAG:-latest}`, igual que `docker-compose.prod.yml`. Si tiene la versión escrita fija, siempre levantaría esa versión.

## Seguridad
- El runner ejecuta en el servidor el código de los workflows. El repo debe mantenerse **privado**.
- No usar `runs-on: self-hosted` en workflows que se disparen con pull requests de terceros.
- Montar `/var/run/docker.sock` le da al contenedor del runner control total de Docker en el servidor, que es equivalente a ser root. Por eso solo debe ejecutar workflows de este repo.
