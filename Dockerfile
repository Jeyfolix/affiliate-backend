# Use PHP with Apache
FROM php:8.2-apache

# Install required extensions for MySQL/TiDB
RUN docker-php-ext-install pdo_mysql mysqli

# Enable Apache mod_rewrite for .htaccess
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy all backend files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configure Apache to allow .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
