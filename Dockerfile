# Use the official PHP image as the base
FROM php:8.0-apache

# Set the working directory inside the container
WORKDIR /var/www/html/public

# Copy your entire application to the container's /var/www/html directory
COPY . /var/www/html

# Ensure Apache has the correct permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

# Set the ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Create a basic Apache configuration to allow access to the /var/www/html/public directory
RUN echo '<Directory "/var/www/html/public">\n\
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
