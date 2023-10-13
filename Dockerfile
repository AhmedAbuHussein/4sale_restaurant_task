FROM php:7.4-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql sockets

# Copy application files
COPY . /var/www

# Set file permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install application dependencies
RUN composer install
RUN chmod 777 -R storage
RUN chmod 644 ./vendor/autoload.php