#!/bin/bash
# Railway build script untuk Laravel

# Install composer dependencies
composer install --no-dev --optimize-autoloader

# Generate app key jika belum ada
php artisan key:generate --force

# Cache config & routes untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migrasi database
php artisan migrate --force

# Buat symlink storage
php artisan storage:link
