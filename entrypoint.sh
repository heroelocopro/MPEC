#!/bin/bash
set -e


# 1. Configurar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache




if [ "$APP_ENV" != "production" ]; then
    echo "Compilando assets en tiempo de ejecución con npm..."
    npm run build
fi

# 6. Migraciones (solo si DB está disponible)
php artisan migrate:fresh --seed --force



# 7. Iniciar servidor
exec apache2-foreground
