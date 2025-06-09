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

# 3. Esperar a que PostgreSQL esté disponible
# echo "Esperando a PostgreSQL..."
# while ! pg_isready -h $DB_HOST -p $DB_PORT -U $DB_USERNAME -d $DB_DATABASE -t 1 >/dev/null 2>&1; do
#     sleep 2
# done

# 4. Limpieza inicial
php artisan config:clear
php artisan view:clear
php artisan event:clear

# 5. Optimización
php artisan optimize

# 6. Migraciones (solo si DB está disponible)
php artisan migrate:fresh --seed --force

# 7. Iniciar servidor
exec apache2-foreground
