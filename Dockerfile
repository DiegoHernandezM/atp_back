# Usa la imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Instala Nginx y otras dependencias junto con el cliente PostgreSQL
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
WORKDIR /var/www/html

# Copia el código de la aplicación al contenedor
COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copia el script init.sh al contenedor
COPY init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# Instala las dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto (opcional, ya que Render asigna uno automáticamente)
EXPOSE ${PORT:-80}

# Ejecutar el script init.sh al inicio
CMD ["sh", "/usr/local/bin/init.sh"]
