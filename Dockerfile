# Use official PHP 8.3 image with Apache
FROM php:8.3-apache

WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev libonig-dev libxml2-dev libpng-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring bcmath ctype xml zip gd \
    && a2enmod rewrite

# Copy project files
COPY . /var/www/html

# Remove old vendor and lock file
RUN rm -rf vendor composer.lock

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
