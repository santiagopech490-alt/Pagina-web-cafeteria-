#!/bin/sh
set -e

# Crear base de datos si no existe
touch /var/www/database/database.sqlite
chmod -R 775 /var/www/database

# Correr migraciones y seeders
php artisan migrate --force
php artisan db:seed --force || true

# Iniciar servidor
exec php artisan serve --host=0.0.0.0 --port=8080
