#!/bin/bash
set -e

# Verificar la conexión a la base de datos PostgreSQL
until php -r "try { new PDO('pgsql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE', '$DB_USERNAME', '$DB_PASSWORD'); echo 'Connected to PostgreSQL database successfully.\n'; } catch (PDOException \$e) { echo 'Connection failed: ' . \$e->getMessage() . '\n'; exit(1); }"; do
  echo "Waiting for PostgreSQL to be ready..."
  sleep 2
done

# Ejecutar las migraciones de Laravel
php artisan migrate --force

# Inicia Nginx y PHP-FPM
php-fpm -D && nginx -g 'daemon off;'
