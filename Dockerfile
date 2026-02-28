# Use PHP with Apache
FROM php:8.2-apache

# Install required extensions
RUN docker-php-ext-install pdo_mysql mysqli

# Enable Apache modules
RUN a2enmod rewrite headers

# Copy all backend files to the correct location
COPY . /var/www/html/

# Ensure all files are copied
RUN ls -la /var/www/html/
RUN ls -la /var/www/html/api/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configure Apache to allow .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
