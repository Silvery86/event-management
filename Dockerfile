# Use the official PHP image with PHP and Composer
FROM php:8.2-fpm

# Install required extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory inside container
WORKDIR /var/www/html

# Copy Laravel project files to container
COPY . .

# Install Composer dependencies
RUN apt-get update && apt-get install -y unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions for Laravel storage
RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
