FROM php:8.2-apache

# 1. Install system dependencies for PostgreSQL and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# 3. Set working directory
WORKDIR /var/www/html

# 4. Copy project files
COPY . /var/www/html

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Install Laravel dependencies
# We use --no-interaction to prevent the build from hanging
RUN composer install --no-interaction --no-dev --optimize-autoloader

# 7. Set permissions for Laravel (Crucial for the 500 error fix)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Point Apache to the Laravel 'public' folder
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# 9. Clear caches and Automate migration
# We clear the config cache first to make sure it reads the new DATABASE_URL
CMD php artisan config:clear && php artisan migrate --force && apache2-foreground