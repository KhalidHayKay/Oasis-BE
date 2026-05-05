#!/bin/sh
set -e

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Caching configuration..."
php artisan config:cache
php artisan route:cache

echo "[$(date +'%Y-%m-%d %H:%M:%S')] Starting PHP-FPM application..."
exec php-fpm
