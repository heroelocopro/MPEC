#!/bin/bash
set -e

cd /var/www/html

# 1. Configurar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache






# 5. Optimización
php artisan optimize



# 6. Migraciones (solo si DB está disponible)
php artisan migrate:fresh --seed --force

# 7. Iniciar servidor
exec apache2-foreground
