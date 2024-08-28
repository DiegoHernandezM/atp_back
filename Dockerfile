# Usa la imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Actualiza los repositorios e instala Nginx, dependencias de PHP, y libpq-dev para PostgreSQL
RUN apt-get update && apt-get install -y nginx \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl

# Configura las extensiones GD para PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instala las extensiones de PHP, incluyendo pdo_pgsql para PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Copia la configuración de Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www

# Copia el código de la aplicación al contenedor
COPY . .

# Instala las dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# Crea enlaces simbólicos, cache y otros ajustes de Laravel
RUN php artisan config:cache
RUN php artisan route:cache

# Exponer el puerto 8080
EXPOSE 8080

# Inicia Nginx y PHP-FPM
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
