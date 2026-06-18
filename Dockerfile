FROM php:8.2-fpm-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache nginx supervisor mariadb-client libpng-dev libjpeg-turbo-dev freetype-dev zip libzip-dev git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip bcmath

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Simple command to start the built-in PHP server for now
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
