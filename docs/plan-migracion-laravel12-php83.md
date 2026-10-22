# Plan: Migrar crmlito a Laravel 12 + PHP 8.3 y dockerizar

## Context
Se quiere una imagen Docker con una versión de PHP estable. La versión actual (Laravel 5.5) obliga a usar PHP 7.4, y esa rama no tiene parche para **PHP2Shell** (CVE-2026-109618 y CVE-2026-109619, RCE crítica, parcheada el 21-oct-2026 solo en 8.2.35, 8.3.36, 8.4.27 y 8.5.12). El objetivo es migrar el código a una versión con soporte y empaquetarlo en una imagen parcheada.

**Destino elegido:**
- **PHP 8.3.36 o superior.** Tiene soporte de seguridad hasta finales de 2027 y el driver `sqlsrv`/`pdo_sqlsrv` 5.12 lo soporta oficialmente.
- **Laravel 12.** Soporta PHP 8.2 a 8.5 y recibe parches de seguridad hasta febrero de 2027. Laravel 10 y 11 ya no tienen soporte.

## Lo que encontré en el código (439 archivos PHP, ~37k líneas en `app/`)
| Área | Uso | Impacto |
|---|---|---|
| Rutas `'Controller@metodo'` | 338 (web.php, candy/elementos_presupuestados.php) | Bajo si se mantiene el namespace de controladores |
| `DB::select/raw/statement` (SQL Server) | 802 | Bajo: SQL crudo, no cambia |
| `count($x)` | 318 | **Alto**: en PHP 8 da `TypeError` si `$x` es null o escalar. Hay que revisarlos |
| `session()` / `Session::` | 462 | Bajo |
| `Mail::` y 6 Mailables | 254 | Medio: SwiftMailer se reemplaza por Symfony Mailer, `MAIL_DRIVER` pasa a `MAIL_MAILER` |
| Carbon (`Carbon::parse` ×44) | 264 | Bajo: Carbon 1 → 3. No se usa `diffIn*`, que es el cambio más peligroso |
| DataTables (`DataTables::of`, add/editColumn) | 69 + 209 | Medio: yajra 8 → 12, revisar `rawColumns`/escapado |
| dompdf (`app('dompdf.wrapper')`) | ~5 archivos + 1 closure en web.php | Bajo: barryvdh/laravel-dompdf 0.8 → 3.x |
| Excel | `Excel::download` OK; `Excel::load()` en `ExcelController.php:47` | Ese `Excel::load` ya está roto hoy (es API 2.x). Se reescribe como Import o se elimina |
| `str_*` / `array_*` helpers | 24 usos en 6 archivos | Se cambian por `Str::` / `Arr::` |
| `utf8_encode/decode` | 12 usos en 3 archivos | Obsoleto en 8.2: se cambian por `mb_convert_encoding` |
| Auth (`AuthenticatesUsers` en `Auth/*Controller.php`) | 4 controladores | Requiere el paquete `laravel/ui` ^4 |
| Modelos en namespace `App\` (no `App\Models`) | ~40 | Se mantienen así; solo se ajusta `config/auth.php` |
| `fideloper/proxy` (`TrustProxies`) | 1 middleware | Se usa el middleware nativo |
| Tests | Solo los de ejemplo | Las pruebas son manuales y se suman tests de humo nuevos |
| **No es repo git** | – | Riesgo: no hay forma de revertir. Paso 0 obligatorio |

## Fases

### Fase 0: Seguridad del trabajo
1. `git init`, un `.gitignore` que excluya `storage/` (2.8 GB), `vendor`, `.env` y `sftp-config.json`, y un commit inicial con el estado actual.
2. Crear la rama `upgrade/laravel12`.
3. Hacer un respaldo de la BD SQL Server y conseguir una BD de pruebas (copia) para validar.

### Fase 1: Entorno Docker de desarrollo (PHP 8.3)
Se crea primero, porque no hay PHP local y servirá para todo el resto de la migración.
- `Dockerfile` multi-stage:
  - Stage `vendor`: `composer:2` con `install --no-dev --prefer-dist --no-scripts` y luego `dump-autoload -o`.
  - Stage final: `php:8.3-apache` (Debian bookworm). Hay que fijar en el build un tag ≥ **8.3.36** (`php:8.3.36-apache` o posterior).
  - Extensiones: `gd` (freetype/jpeg), `zip`, `intl`, `bcmath`, `opcache`, `pdo`, más `pecl sqlsrv-5.12.0 pdo_sqlsrv-5.12.0` y `msodbcsql18` del repo de Microsoft.
  - Apache con `mod_rewrite` y DocumentRoot en `/var/www/html/public`.
- `docker/php.ini`: `date.timezone=America/Guatemala`, `memory_limit=512M`, uploads de 100M, opcache, `expose_php=Off`.
- `docker/entrypoint.sh`: crea la estructura de `storage/` en el volumen, ajusta permisos y, en producción, corre `config:cache`, `route:cache` y `view:cache`.
- `.dockerignore`: `storage/*`, `vendor`, `node_modules`, `.env`, `sftp-config.json`, `.git`.
- `docker-compose.yml`: servicio `app` con puerto 8080, `env_file: .env` y volumen `./storage:/var/www/html/storage`. El SQL Server sigue siendo externo.
- Añadir `'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', false)` a la conexión `sqlsrv` en `config/database.php` (con ODBC 18, `Encrypt=yes` viene activado por defecto).

### Fase 2: Esqueleto Laravel 12 + dependencias
Se salta directo de 5.5 a 12, sin pasar por cada versión intermedia. La app es mayormente controladores, Blade y SQL crudo, así que es más barato partir de un esqueleto limpio y portar el código que aplicar 7 guías de upgrade.
1. Generar un esqueleto Laravel 12 nuevo en un directorio temporal y traer a este proyecto:
   - `bootstrap/app.php`, `artisan`, `public/index.php` y `config/*.php`, fusionando los valores propios: `app.php` (timezone, locale `es`), `database.php` (sqlsrv), `mail.php`, `filesystems.php`, y conservando `ws_mv.php`, `datatables.php`, `dompdf.php` y `flasher.php` regenerados con `vendor:publish`.
   - Eliminar `server.php`, `app/Http/Kernel.php`, `app/Console/Kernel.php` y `app/Exceptions/Handler.php`. Su contenido pasa a `bootstrap/app.php`.
2. `composer.json` nuevo:
   - `php ^8.3`, `laravel/framework ^12.0`, `laravel/tinker ^2.10`, `laravel/ui ^4.6`
   - `barryvdh/laravel-dompdf ^3.1`, `maatwebsite/excel ^3.1` (última versión), `yajra/laravel-datatables-oracle ^12`, `php-flasher/flasher-laravel ^2`
   - Dev: `barryvdh/laravel-debugbar ^3`, `fakerphp/faker`, `mockery/mockery ^1.6`, `phpunit/phpunit ^11`, `nunomaduro/collision ^8`
   - Se quitan `fideloper/proxy`, `fzaninotto/faker`, `filp/whoops` y `symfony/thanks`. Se mantiene `"files": ["app/funciones.php"]` en autoload.
3. `bootstrap/app.php`:
   - `withRouting(using: ...)` registra `routes/web.php` y `routes/candy/elementos_presupuestados.php` con `->namespace('App\Http\Controllers')`. Así las 338 rutas `'X@y'` siguen funcionando sin reescribirlas.
   - `withMiddleware`: alias `accessElementosPresupuestados` y `TrustProxies` nativo (sustituye al de `app/Http/Middleware/TrustProxies.php`). Se conservan `EncryptCookies` y `VerifyCsrfToken` con sus excepciones (`$except`).
   - `withSchedule`: los comandos `HoyLlamadas`, `HoyReuniones` y `HoyReuniones1Hora` (hoy en `app/Console/Kernel.php`).
4. Providers: `bootstrap/providers.php` con `AppServiceProvider`, `AuthServiceProvider` y `EventServiceProvider`. Los de paquetes se autodescubren, así que se quitan de `config/app.php` los registros manuales de Yajra, Excel y Flasher.
5. `.env`: `MAIL_DRIVER` → `MAIL_MAILER`, `QUEUE_DRIVER` → `QUEUE_CONNECTION`, `CACHE_DRIVER` → `CACHE_STORE`.

### Fase 3: Adaptar código a PHP 8.3 / Laravel 12
Se trabaja por patrón, en todo el repo:
- **`count()`**: revisar los 318 usos. Donde el valor puede ser null o un escalar (resultados de `DB::select(...)[0]`, `->first()`, inputs), cambiar a `count($x ?? [])` o a `empty()`/`isset()`. Los peores focos son `OportunidadesController.php`, `OportunidadesControllerDev.php` y `ElementosPresupuestadosV2Controller.php`.
- Helpers `str_*` / `array_*` → `Illuminate\Support\Str` / `Arr` (24 usos).
- `utf8_encode/decode` → `mb_convert_encoding($s, 'UTF-8', 'ISO-8859-1')` y su inverso (12 usos).
- `ExcelController.php:47`: reescribir `Excel::load($path)->get()` como `Excel::toCollection(new XImport, $path)`.
- dompdf: `app('dompdf.wrapper')` sigue existiendo. Revisar `setPaper`/`loadView` en `EstructuralController.php`, `ElementosPresupuestadosV2Controller.php` y el closure de `routes/web.php:653`. Revisar también `config/dompdf.php` (`enable_remote` y `chroot` cambiaron en 2.x/3.x).
- Mailables (`app/Mail/*`): adaptar `attach`/`attachData` y cualquier uso de SwiftMailer. Revisar `MAIL_ENCRYPTION` y `MAIL_SCHEME`.
- DataTables: confirmar `rawColumns()` en las columnas con HTML (`addColumn`/`editColumn` escapan por defecto desde la v9).
- Modelos: `$dates` → `$casts` donde aplique. Marcar `$fillable`/`$guarded` explícitos si la asignación masiva falla.
- Auth: `laravel/ui` proporciona `AuthenticatesUsers`. Validar `LoginController` y el modelo `App\User` en `config/auth.php`.
- Borrar archivos muertos que rompen el autoload o confunden: `OportunidadesController copy.php` y vistas `*.bladeR.php`. Se confirma con el usuario antes de borrarlos.
- Correr una herramienta de análisis para cazar lo que falte:
  - `vendor/bin/phpstan analyse app --level=1` con `larastan`, solo en dev.
  - `php -l` sobre todo `app/`.
  - Opcional: `rector` con los sets `LevelSetList::UP_TO_PHP_83` y `LaravelSetList` para los cambios mecánicos.

### Fase 4: Pruebas
- Tests de humo nuevos en `tests/Feature/SmokeTest.php`. Recorren las rutas GET principales con un usuario autenticado y verifican que no devuelven 500.
- Checklist manual contra la BD de pruebas: login/logout, panel, clientes, contactos, oportunidades (crear, editar, adjuntar documento), elementos presupuestados v2, estructural (PDF), exportar Excel de clientes, listados DataTables, envío de correo (con `MAIL_MAILER=log`), entregas, agenda y los 3 comandos programados (`php artisan schedule:test`).
- Revisar `storage/logs/laravel.log` buscando deprecations y TypeErrors.

### Fase 5: Imagen de producción
- `docker build -t crmlito:8.3 .`
- Escaneo: `docker scout cves crmlito:8.3` o `trivy image crmlito:8.3`. Confirmar `php -v` ≥ 8.3.36.
- Despliegue con `storage/` montado como volumen (los 2.8 GB actuales).

## Verificación final
1. `docker compose exec app php -v` → 8.3.36 o superior (parcheado contra PHP2Shell).
2. `docker compose exec app php -m` incluye sqlsrv, pdo_sqlsrv, gd, zip, intl y opcache.
3. `docker compose exec app php artisan about` muestra Laravel 12, y `route:list` lista todas las rutas.
4. `docker compose exec app php artisan tinker --execute="DB::select('select 1')"` conecta a SQL Server.
5. `docker compose exec app php artisan test` pasa los tests de humo.
6. El checklist manual de la Fase 4 queda completo sin errores en el log.

## Estado (22-oct-2026)
- Fases 0–3 hechas en la rama `upgrade/laravel12` (commits f12c286, 9b2002d, c5d72ce, cfb9b2f).
- Imagen `crmlito:8.3` compila; `/crmlito/up` responde 200 y las 461 rutas cargan.
- **Siguiente: Fase 4.** Está bloqueada hasta tener una BD de pruebas (SQL Server). Con ella se corre `SMOKE_LOGIN=<usuario> php artisan test` y el checklist manual.
- Pendiente del usuario: rotar la contraseña de Gmail de noreply, indicar la versión de PHP actual en producción y decidir si se borra el código muerto (`*copy.php`, `*Dev.php`, `ProspectosRController.php`, `Auth/*`, la importación de Excel del tutorial).

## Fuera de alcance (pospuesto por el usuario)
Exposición de `PswMail`, la contraseña de Gmail de cada agente:
- `getDocumentoOportMail` y `getDocumentoEstrMail` la devuelven al navegador sin pedir `auth`.
- El JS la reenvía en `enviarDocumentoMail`.

Corrección propuesta para más adelante:
- El servidor la obtiene de la BD y deja de exponerla.
- Middleware `auth` en esas rutas.
- A largo plazo, enviar siempre con la cuenta noreply y el agente en Reply-To.

## Estimación
Unas 2 a 4 semanas de una persona. El grueso está en la Fase 3 (los `count()` y los controladores de más de 3k líneas) y en las pruebas manuales, porque no hay tests existentes.
