FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP (incluida pdo_sqlite)
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Crear el archivo sqlite si no existe y dar permisos
RUN touch /var/www/database/database.sqlite && chmod -R 775 /var/www/database

# Al iniciar el contenedor, ejecutar las migraciones antes de levantar el servidor
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
