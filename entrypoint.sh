#!/bin/bash
set -e

# Cambiar al directorio de la aplicación
cd /var/www/html

# Configurar permisos (como www-data)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Generar clave de aplicación si no existe
if [ ! -f ".env" ]; then
  cp .env.example .env
  php artisan key:generate
fi

# Ejecutar migraciones
php artisan migrate --force

# Optimizar para producción
php artisan optimize

# Reconstruir assets si es necesario
npm run build

# Iniciar Apache en primer plano
exec apache2-foreground
