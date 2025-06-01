#!/bin/bash

# Run Composer only if vendor doesn't exist
if [ ! -d "vendor" ]; then
  composer install
fi

# Wait until MySQL is ready
until nc -z -v -w30 mysql 3306
do
  echo "Waiting for MySQL connection..."
  sleep 2
done

# Laravel setup
php artisan key:generate --force
php artisan migrate:refresh --seed --force

# Start PHP-FPM
exec php-fpm
