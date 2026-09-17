#!/bin/sh
set -e

# Render (and most PaaS) inject the port to listen on via $PORT
PORT="${PORT:-80}"
sed -ri "s/^Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Make sure Laravel's writable directories exist
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

# Prepare the SQLite database when it is the configured connection
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-database/database.sqlite}"
    touch "${DB_FILE}"
    chown www-data:www-data "${DB_FILE}"
fi

chown -R www-data:www-data storage bootstrap/cache

# Link public storage (ignore if it already exists)
php artisan storage:link || true

# Run migrations (retry while the database is still starting up) and seed
for attempt in 1 2 3 4 5; do
    if php artisan migrate --force; then
        break
    fi
    echo "Database not ready (attempt ${attempt}), retrying in 3s..."
    sleep 3
done

php artisan db:seed --force || true
php artisan config:cache
php artisan view:cache

exec apache2-foreground
