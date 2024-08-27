# Usa la imagen oficial de PHP con FPM
FROM php:8.1-fpm

# Instala dependencias necesarias, incluyendo la extensión zip
RUN apt-get update && apt-get install -y \
    build-essential \
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

# Establece el puerto que expone el contenedor
EXPOSE 80

# Comando para ejecutar la aplicación Laravel
CMD ["php-fpm"]
