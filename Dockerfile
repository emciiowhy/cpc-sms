FROM php:8.2-apache

# 1. Install system dependencies + Node.js (needed for Vite)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 3. Set working directory
WORKDIR /var/www/html

# 4. Copy project files
COPY . /var/www/html

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction --no-dev --optimize-autoloader

# 6. Build Frontend Assets (Vite)
# This creates the manifest.json file Laravel is looking for
RUN npm install
RUN npm run build

# 7. Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Point Apache to public/
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# 9. Entrypoint script (Ensure this file exists in your repo)
RUN chmod +x /var/www/html/docker-entrypoint.sh
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]