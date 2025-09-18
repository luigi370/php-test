FROM php:8.3-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Install Xdebug only if not already installed
RUN pecl install xdebug-3.4.5 || true && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json composer.lock* ./
# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Enable Apache rewrite module (optional, for flexibility)
RUN a2enmod rewrite

# Set permissions for the web directory
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Set Apache document root (matches APACHE_DOCUMENT_ROOT in docker-compose.yml)
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf