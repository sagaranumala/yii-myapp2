FROM php:7.4-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# # Copy Yii framework (optional if already in container)
# COPY ../yii-1.1.18.018a89 /var/www/yii-framework

# # Copy your app
# COPY ../myapp2 /var/www/html

# Set working directory
WORKDIR /var/www/html

# Allow .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80
