# Use PHP with Apache
FROM php:8.2-apache

# Install required extensions
RUN docker-php-ext-install pdo_mysql mysqli

# Enable Apache modules
RUN a2enmod rewrite headers

# Set ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy all backend files to the correct location
COPY . /var/www/html/

# Ensure index.php exists (create if not)
RUN if [ ! -f /var/www/html/index.php ]; then \
    echo '<?php echo json_encode(["status" => "ok", "message" => "API is running"]); ?>' > /var/www/html/index.php; \
    fi

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configure Apache to use /var/www/html as root and allow .htaccess
RUN sed -i 's|/var/www/html|/var/www/html|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Create a test file to verify
RUN echo "<?php phpinfo(); ?>" > /var/www/html/test.php

# List files for debugging (will show in build logs)
RUN echo "=== Files in /var/www/html ===" && ls -la /var/www/html/
RUN echo "=== Files in /var/www/html/api ===" && ls -la /var/www/html/api/ || echo "api directory not found"

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
