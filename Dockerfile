FROM php:8.3-fpm

# Instalar dependencias del sistema y extensiones de PHP (incluida sqlite)
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev zip curl sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

# Crear directorio de base de datos, archivo sqlite y asignar permisos
RUN mkdir -p /var/www/database && \
    touch /var/www/database/database.sqlite && \
    chmod -R 777 /var/www/database /var/www/storage /var/www/bootstrap/cache

# Comando de arranque completo
CMD sh -c "php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8080"
