# syntax=docker/dockerfile:1

# PHP >= 8.3.36 incluye el parche de PHP2Shell (CVE-2026-109618 / CVE-2026-109619).
ARG PHP_VERSION=8.3.36

# ---------------------------------------------------------------------------
# base: PHP + Apache + extensiones (incluye SQL Server). Se usa en desarrollo.
# ---------------------------------------------------------------------------
FROM php:${PHP_VERSION}-apache-bookworm AS base

ARG SQLSRV_VERSION=5.12.0

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        ca-certificates curl gnupg unzip git \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev libicu-dev libxml2-dev libonig-dev; \
    # Driver ODBC 18 de Microsoft para SQL Server
    curl -fsSL https://packages.microsoft.com/keys/microsoft.asc \
        | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg; \
    curl -fsSL https://packages.microsoft.com/config/debian/12/prod.list \
        -o /etc/apt/sources.list.d/mssql-release.list; \
    apt-get update; \
    ACCEPT_EULA=Y apt-get install -y --no-install-recommends msodbcsql18 unixodbc-dev; \
    # Extensiones nativas
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" gd zip intl bcmath opcache pdo; \
    # Extensiones de SQL Server
    pecl install sqlsrv-${SQLSRV_VERSION} pdo_sqlsrv-${SQLSRV_VERSION}; \
    docker-php-ext-enable sqlsrv pdo_sqlsrv; \
    # Limpieza
    apt-get purge -y --auto-remove gnupg; \
    rm -rf /var/lib/apt/lists/* /tmp/pear

# Apache: la app se sirve en la raíz (ver docker/apache-vhost.conf)
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
RUN set -eux; \
    a2enmod rewrite headers; \
    printf 'ServerTokens Prod\nServerSignature Off\n' > /etc/apache2/conf-available/zz-security.conf; \
    a2enconf zz-security

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
ENV PHP_OPCACHE_VALIDATE_TIMESTAMPS=0
COPY docker/php.ini $PHP_INI_DIR/conf.d/zz-app.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint
ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]

# ---------------------------------------------------------------------------
# vendor: dependencias de Composer sin paquetes de desarrollo
# ---------------------------------------------------------------------------
FROM base AS vendor

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

# ---------------------------------------------------------------------------
# production: imagen final
# ---------------------------------------------------------------------------
FROM base AS production

ENV APP_ENV=production

COPY --chown=www-data:www-data . .
COPY --from=vendor --chown=www-data:www-data /var/www/html/vendor ./vendor

RUN set -eux; \
    mkdir -p storage/app/public storage/framework/cache storage/framework/sessions \
             storage/framework/views storage/logs bootstrap/cache; \
    chown -R www-data:www-data storage bootstrap/cache
