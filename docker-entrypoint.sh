#!/bin/sh

# 1. Optimize for Production
# This caches your config and routes so the app runs faster
php artisan optimize

# 2. Run Migrations AND Seeders
# We use --force because Laravel protects production databases from accidental wipes
php artisan migrate:fresh --seed --force

# 3. Clear any leftover cache just in case
php artisan cache:clear

# 4. Start the Web Server
exec apache2-foreground