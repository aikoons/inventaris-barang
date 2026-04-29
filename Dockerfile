FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first (cache layer)
COPY composer.json composer.lock ./

# Install PHP dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy app source
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Laravel setup
RUN php artisan storage:link || true

EXPOSE 8000

CMD sh -c "php artisan key:generate --force && \
           php artisan migrate --force && \
           php artisan config:cache && \
           php artisan route:cache && \
           php artisan view:cache && \
           php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"
