FROM php:8.2-apache

# Variables de entorno para producción
ENV APP_ENV=production
ENV APP_DEBUG=false

# Configurar variables de base de datos por defecto
ENV DB_CONNECTION=mysql
ENV DB_HOST=mysql
ENV DB_PORT=3306
ENV DB_DATABASE=laravel
ENV DB_USERNAME=root
ENV DB_PASSWORD=

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql zip mbstring exif pcntl bcmath gd

# Instalar Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# 1. Copiar SOLO los archivos necesarios para composer
COPY composer.json composer.lock /var/www/html/

# 2. Instalar dependencias de Composer sin scripts
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# 3. Ahora copiar el resto de los archivos
COPY . .

# 4. Crear archivo SQLite si es necesario (para evitar errores)
RUN touch database/database.sqlite && \
    chown www-data:www-data database/database.sqlite && \
    chmod 644 database/database.sqlite

# 5. Ejecutar los scripts de composer que requieren artisan
RUN composer run-script post-autoload-dump

# 6. Configurar caché para usar array en lugar de base de datos durante el build
RUN sed -i "s/'default' => env('CACHE_DRIVER', 'file')/'default' => 'array'/" config/cache.php

# 7. Limpiar cachés (ahora con CACHE_DRIVER=array)
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan optimize

# 8. Restaurar configuración original de caché
RUN sed -i "s/'default' => 'array'/'default' => env('CACHE_DRIVER', 'file')/" config/cache.php

# 9. Instalar dependencias de Node y construir assets
RUN npm install && npm run build

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Apache
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf && \
    a2enmod rewrite

# Configurar alias para los assets de Vite
RUN echo 'Alias /build /var/www/html/public/build' >> /etc/apache2/apache2.conf && \
    echo '<Directory /var/www/html/public/build>' >> /etc/apache2/apache2.conf && \
    echo '    Require all granted' >> /etc/apache2/apache2.conf && \
    echo '    Options FollowSymLinks' >> /etc/apache2/apache2.conf && \
    echo '</Directory>' >> /etc/apache2/apache2.conf && \
    echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Configurar entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exponer puerto
EXPOSE 80

# Comando de inicio
USER www-data
CMD ["entrypoint.sh"]
