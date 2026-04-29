#!/bin/sh

echo "=== Laravel Startup ==="

# Migrate (jangan exit kalau gagal)
php artisan migrate --force || echo "[WARN] Migration failed - DB mungkin belum terhubung"

# Cache
php artisan config:cache  || true
php artisan route:cache   || true
php artisan view:cache    || true

echo "=== Starting server on port ${PORT:-8000} ==="
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
