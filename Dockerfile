# Use the official PHP image as the base
FROM php:8.0-apache

# Set the working directory inside the container
WORKDIR /var/www/html/public

# Copy the entire application to the container's /var/www/html directory
COPY . /var/www/html

# Set ownership and permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Enable Apache modules for rewrite and other features
RUN a2enmod rewrite

# Ensure Apache allows .htaccess override
RUN echo '<Directory "/var/www/html/public">\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/docker.conf

# Enable the new configuration
RUN a2enconf docker

# Install PHP extensions if necessary
RUN docker-php-ext-install pdo pdo_mysql

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
