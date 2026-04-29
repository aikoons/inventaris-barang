#!/bin/sh

echo "=== Laravel Startup ==="

# Generate app key
php artisan key:generate --force

# Migrate (jangan stop kalau gagal - server tetap harus jalan)
php artisan migrate --force || echo "[WARN] Migration failed or DB not ready"

# Cache (ignore errors)
php artisan config:cache  || true
php artisan route:cache   || true
php artisan view:cache    || true

echo "=== Starting PHP server on 0.0.0.0:${PORT:-8000} ==="
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
