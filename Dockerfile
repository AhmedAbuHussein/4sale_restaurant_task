FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update
RUN apt-get install -y zip unzip git curl libpng-dev libonig-dev libxml2-dev 

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Install PHP extensions
RUN docker-php-ext-configure opcache --enable-opcache
RUN docker-php-ext-install pdo pdo_mysql gd


# Copy application files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set file permissions
# RUN chown -R www-data:www-data .
RUN chmod 777 -R /var/www/storage

CMD [ "composer", "install"]
