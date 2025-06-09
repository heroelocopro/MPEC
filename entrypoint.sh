#!/bin/bash
set -e

# Esperar a que la base de datos esté disponible (opcional pero recomendado)
while ! php artisan db:query "SELECT 1" >/dev/null 2>&1; do
  echo "Esperando a que la base de datos esté disponible..."
  sleep 5
done

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

# Reconstruir assets (útil para entornos como Render)
npm run build

# Optimizar para producción
php artisan optimize

# Iniciar Apache en primer plano
exec apache2-foreground
