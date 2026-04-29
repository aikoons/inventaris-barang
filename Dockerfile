FROM php:8.3-cli

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first (better cache layer)
COPY composer.json composer.lock ./

# Install PHP dependencies (ignore platform reqs since extensions are installed above)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    --ignore-platform-reqs

# Copy app source
COPY . .

# Run composer scripts now that full app is available
RUN composer dump-autoload --optimize --no-dev

# Set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions \
             storage/framework/views bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Storage link (may fail gracefully if already exists)
RUN php artisan storage:link || true

EXPOSE 8000

CMD ["/bin/sh", "-c", "php artisan key:generate --force && php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
