#!/bin/sh

# Clear old settings
php artisan config:clear
php artisan cache:clear

# Run migrations
php artisan migrate --force

# Start Apache in the foreground
exec apache2-foreground