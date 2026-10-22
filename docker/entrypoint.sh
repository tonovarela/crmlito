#!/bin/sh
set -e

cd /var/www/html

# storage/ suele venir de un volumen: asegurar la estructura que Laravel necesita
mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage/framework storage/logs bootstrap/cache

if [ -f vendor/autoload.php ]; then
    php artisan package:discover --ansi

    if [ "$APP_ENV" = "production" ]; then
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
    fi
fi

exec "$@"
