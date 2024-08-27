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

# Copia el archivo SQL al contenedor
COPY ./quizz.sql /docker-entrypoint-initdb.d/quizz.sql

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

# Copia el script wait-for-mysql.sh al contenedor
COPY wait-for-mysql.sh /usr/local/bin/wait-for-mysql.sh
RUN chmod +x /usr/local/bin/wait-for-mysql.sh

# Ejecutar la importación del dump SQL
CMD ["sh", "-c", "mysql -h db -u root -psecret quizz < /docker-entrypoint-initdb.d/quizz.sql && php-fpm -D && nginx -g 'daemon off;'"]

# Exponer el puerto 8080
EXPOSE 8080