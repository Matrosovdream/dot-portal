FROM php:8.3-cli

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app source
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www

CMD php artisan serve --host=0.0.0.0 --port=8000
