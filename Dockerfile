# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

# Instala Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el código fuente al directorio por defecto de Apache
COPY . /var/www/html

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Instala las dependencias de PHP con Composer (sin dev)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Ajusta permisos para storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configura Apache para que use la carpeta public de Laravel como DocumentRoot
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Habilita mod_rewrite para Laravel
RUN a2enmod rewrite


# Expone el puerto 80 para Apache
EXPOSE 80

# Comando para iniciar Apache en primer plano
CMD ["apache2-foreground"]

