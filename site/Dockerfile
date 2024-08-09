FROM php:8.3.8-apache

# Enable Apache2 mod_rewrite
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Create logs directory
RUN mkdir -p /var/www/html/logs \
    && chown -R www-data:www-data /var/www/html/logs \
    && chmod -R 755 /var/www/html/logs

# Copy site files to the Apache document root
COPY . /var/www/html/

# Set permissions for the Apache document root
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Change to the working directory
WORKDIR /var/www/html

# Expose ports
EXPOSE 80
