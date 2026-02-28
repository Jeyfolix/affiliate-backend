# Use PHP with Apache
FROM php:8.2-apache

# Install required extensions and ca-certificates
RUN apt-get update && \
    apt-get install -y ca-certificates && \
    docker-php-ext-install pdo_mysql mysqli && \
    docker-php-ext-enable pdo_mysql mysqli

# Enable Apache modules
RUN a2enmod rewrite headers

# Set ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy all files
COPY . /var/www/html/

# Show what was copied
RUN echo "=== Files in /var/www/html ===" && ls -la /var/www/html/
RUN echo "=== Files in /var/www/html/api ===" && ls -la /var/www/html/api/ || echo "No api directory"

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Create a simple test file
RUN echo "<?php echo 'PHP is working!'; ?>" > /var/www/html/test.php

# Configure Apache
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf && \
    echo '    DocumentRoot /var/www/html' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    <Directory /var/www/html>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
