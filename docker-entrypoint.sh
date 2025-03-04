#!/bin/bash

set -e

cd /var/www/html/

# Run Laravel setup commands
php artisan migrate
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear

chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Start cron and verify it's running
service cron start
if [ $? -eq 0 ]; then
    echo "Cron service started successfully"
else
    echo "Failed to start cron service"
    exit 1
fi

# Debugging: List running cron jobs
echo "Active cron jobs:"
crontab -l

# Tail cron log for debugging (optional, remove in production)
touch /var/log/cron.log
tail -f /var/log/cron.log &

# Start php-fpm as the main process
exec php-fpm
