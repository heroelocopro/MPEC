FROM php:8.2-apache

# 1. Configuración básica
ENV APP_ENV=production
ENV APP_DEBUG=false
WORKDIR /var/www/html

# 2. Instalar dependencias del sistema (añade postgresql-client)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    libpq-dev postgresql-client \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql zip mbstring exif pcntl bcmath gd

# 3. Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar solo los archivos necesarios inicialmente
COPY composer.json composer.lock package.json /var/www/html/

# 6. Instalar dependencias de Composer SIN scripts
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# 7. Copiar el resto de la aplicación
COPY . .

# 8. Configuración temporal para el build
# RUN touch database/database.sqlite && \
#     chown www-data:www-data database/database.sqlite && \
#     chmod 644 database/database.sqlite

# 9. Ejecutar scripts de Composer (requiere artisan)
RUN composer run-script post-autoload-dump

# 10. Limpieza de caché SIN base de datos
# RUN php artisan config:clear && \
#     php artisan view:clear && \
#     php artisan event:clear

# RUN php artisan livewire:install || true

# 11. Construcción de assets (hazlo ANTES de copiar todo)
RUN npm install && npm run build

# 12. Configuración de Apache
RUN a2enmod rewrite && \
    sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 13. Permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache

# 14. Entrypoint
COPY entrypoint.sh /usr/local/bin/

# Después de COPY . .
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 storage bootstrap/cache
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
CMD ["entrypoint.sh"]
