#!/bin/bash
set -e

cd /var/www/html

# 1. Configurar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 2. Configurar entorno si no existe
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# 3. Limpieza inicial
php artisan config:clear
php artisan view:clear
php artisan event:clear

# 4. Optimización
php artisan optimize

php artisan migrate:fresh --seed --force

# 6. Iniciar servidor
exec apache2-foreground
