#!/bin/bash
set -e

# Esperar a que la base de datos esté disponible (opcional, mejora la estabilidad)
until php artisan migrate:status > /dev/null 2>&1; do
  echo "Esperando a que la base de datos esté disponible..."
  sleep 5
done

# Ejecutar migraciones con --force para no pedir confirmación
php artisan migrate --force

# Luego iniciar Apache en primer plano (como antes)
apache2-foreground
