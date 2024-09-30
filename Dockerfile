# Use the official PHP image as the base
FROM php:8.0-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP application files to the container
COPY . /var/www/html

# Set ownership to Apache's user (www-data) and set correct permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Enable Apache mod_rewrite and other necessary modules
RUN a2enmod rewrite

# Create a basic Apache configuration to allow access to the /var/www/html directory
RUN echo '<Directory "/var/www/html">\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/docker.conf

# Enable the new Apache configuration
RUN a2enconf docker

# Install PHP extensions if necessary
RUN docker-php-ext-install pdo pdo_mysql

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
