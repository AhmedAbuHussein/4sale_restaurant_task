FROM php:7.4-apache-buster

# Set working directory
WORKDIR /var/www/

ENV APP_ENV=dev
ENV APP_DEBUG=true
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install dependencies
RUN apt-get update
RUN apt-get install -y zip unzip git curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy application files
COPY . /var/www/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
     --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --prefer-dist --no-interaction

# server host config
COPY cli/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY .env.example /var/www/.env


# Set file permissions
RUN chown -R www-data:www-data /var/www/
RUN chmod 777 -R /var/www/storage

RUN a2enmod rewrite