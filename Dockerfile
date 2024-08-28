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
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Copia la configuración de Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia el código de la aplicación al contenedor
COPY . .

# Instala las dependencias de PHP con Composer
RUN composer install --no-dev --optimize-autoloader

# Test de conexión a la base de datos PostgreSQL
RUN php -r "try { new PDO('pgsql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'Connected to PostgreSQL database successfully.'; } catch (PDOException $e) { echo 'Connection failed: ' . $e->getMessage(); exit(1); }"

# Crea enlaces simbólicos, cache y otros ajustes de Laravel
RUN php artisan config:cache
RUN php artisan route:cache

# Ejecutar migraciones (asegúrate de que la base de datos esté accesible)
RUN php artisan migrate --force

# Configurar Nginx para que escuche en el puerto proporcionado por Render
RUN sed -i "s/listen 80;/listen ${PORT:-80};/" /etc/nginx/nginx.conf

# Exponer el puerto (opcional, ya que Render asigna uno automáticamente)
EXPOSE ${PORT:-80}

# Inicia Nginx y PHP-FPM
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
