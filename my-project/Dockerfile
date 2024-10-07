# Use an official PHP image with Apache
FROM php:8.1-apache

# Install any required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory to /var/www/html (same as in docker-compose)
WORKDIR /var/www/html

# Copy current directory contents to the working directory in the container
COPY . /var/www/html

# Change ownership of the web root to www-data (Apache's default user)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the Apache web server
EXPOSE 80
