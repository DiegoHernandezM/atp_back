# Usa la imagen oficial de PHP con FPM
FROM php:8.1-fpm

# Instala Nginx y otras dependencias
RUN apt-get update && apt-get install -y nginx \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

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

# Exponer el puerto 80
EXPOSE 80

# Comando para iniciar Nginx junto con PHP-FPM
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
