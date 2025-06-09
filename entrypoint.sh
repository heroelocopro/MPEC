#!/bin/bash
set -e

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
php artisan migrate:fresh --seed --force

# Optimizar para producción
php artisan optimize

# Iniciar Apache
exec apache2-foreground
