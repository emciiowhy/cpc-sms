#!/bin/sh

# Optimize the application for production
# This combines config:cache, route:cache, and view:cache
php artisan optimize

# Run migrations and seed the database with your test accounts
# The --force flag is required to run migrations in production
php artisan migrate --force --seed

# Start Apache in the foreground
exec apache2-foreground