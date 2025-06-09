# Establecer imagen base
FROM php:8.2-apache

# Variables de entorno para producción
ENV APP_ENV=production
ENV APP_DEBUG=false

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

# Copiar archivos necesarios para instalar dependencias (optimización de caché Docker)
COPY composer.json composer.lock package.json vite.config.js tailwind.config.js postcss.config.js /var/www/html/
COPY resources/ /var/www/html/resources/

# Instalar dependencias de Node y construir assets
RUN npm install && npm run build

# Copiar el resto de la aplicación
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Limpiar cachés
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan view:clear && \
    php artisan optimize

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
CMD ["entrypoint.sh"]
