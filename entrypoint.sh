#!/bin/bash
set -e

# Cambiar al directorio de la aplicación
cd /var/www/html

# Configurar permisos
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Crear archivo SQLite si no existe (si es tu driver)
if [ "$DB_CONNECTION" == "sqlite" ] && [ ! -f "database/database.sqlite" ]; then
    touch database/database.sqlite
    chown www-data:www-data database/database.sqlite
    chmod 644 database/database.sqlite
fi

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Generar clave de aplicación si no existe
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Ejecutar migraciones (solo si la base de datos está disponible)
php artisan migrate --force

# Optimizar para producción
php artisan optimize

# Reconstruir assets si es necesario
npm run build

# Iniciar Apache en primer plano
exec apache2-foreground
