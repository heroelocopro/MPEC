FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql zip mbstring exif pcntl bcmath gd

# Instalar Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs

# Copiar Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir directorio de trabajo
WORKDIR /var/www/html

# Copiar todo el proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Instalar dependencias JS y compilar Vite
RUN npm install && npm run build

# Asignar permisos
RUN chown -R www-data:www-data /var/www/html

# Configurar Apache para servir desde public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Asegurar permisos para public/
RUN chown -R www-data:www-data /var/www/html/public

# Habilitar módulos de Apache y acceso a /public
RUN a2enmod rewrite && \
    echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

RUN echo "ServerName mpec.onrender.com" >> /etc/apache2/apache2.conf


# Copiar script de entrada
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Exponer puerto HTTP
EXPOSE 80

# Ejecutar el script de inicio
CMD ["entrypoint.sh"]
