# Use the official PHP 8.1 image as the base
FROM php:8.1-apache

# Set the working directory inside the container
WORKDIR /var/www/html/public

# Copy your entire application to the container's /var/www/html directory
COPY . /var/www/html

# Ensure Apache has the correct permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Enable Apache modules
RUN a2enmod rewrite headers

# Install common PHP extensions needed for microservices and security
RUN apt-get update && apt-get install -y libzip-dev libpng-dev libjpeg-dev libfreetype6-dev unzip git libonig-dev libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Clear apt cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the ServerName to suppress Apache warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Allow access to the public directory and secure Apache configuration
RUN echo '<Directory "/var/www/html/public">\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/docker.conf

# Enable the new Apache configuration
RUN a2enconf docker

# Improve security headers for Apache
RUN echo '<IfModule mod_headers.c>\n\
    Header always set X-Content-Type-Options "nosniff"\n\
    Header always set X-Frame-Options "DENY"\n\
    Header always set X-XSS-Protection "1; mode=block"\n\
    Header always set Referrer-Policy "strict-origin-when-cross-origin"\n\
</IfModule>\n' >> /etc/apache2/conf-available/security-headers.conf \
    && a2enconf security-headers

# Expose port 80 for the web server
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
