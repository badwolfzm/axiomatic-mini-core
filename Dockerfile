# Use the official PHP image as the base
FROM php:8.0-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy your PHP application files to the container
COPY . /var/www/html

# Ensure that Apache has the right permissions to serve the content
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Install any dependencies if needed (e.g., extensions)
RUN docker-php-ext-install pdo pdo_mysql

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
