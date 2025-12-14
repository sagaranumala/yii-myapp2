# # # Use PHP 7.4 with Apache
# # FROM php:7.4-apache

# # # Enable Apache rewrite module
# # RUN a2enmod rewrite

# # # Install required PHP extensions
# # RUN docker-php-ext-install pdo pdo_mysql mysqli

# # # Set working directory
# # WORKDIR /var/www/html

# # # Allow .htaccess overrides
# # RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# # # Expose port 80
# # EXPOSE 80

# # # Start Apache in the foreground
# # CMD ["apache2-foreground"]


# # Use PHP 7.4 with Apache (compatible with Yii 1.x)
# FROM php:7.4-apache

# # Set environment variables
# ENV APACHE_DOCUMENT_ROOT /var/www/html
# ENV COMPOSER_ALLOW_SUPERUSER 1

# # Update package list and install required dependencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     libzip-dev \
#     zip \
#     unzip \
#     libssl-dev \
#     libcurl4-openssl-dev \
#     libfreetype6-dev \
#     libjpeg62-turbo-dev \
#     libpng-dev \
#     wget \
#     nano \
#     vim \
#     iputils-ping \
#     net-tools \
#     && rm -rf /var/lib/apt/lists/*

# # Install PHP extensions
# RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
#     && docker-php-ext-install \
#     pdo \
#     pdo_mysql \
#     mysqli \
#     mbstring \
#     exif \
#     pcntl \
#     bcmath \
#     gd \
#     zip \
#     curl \
#     json \
#     simplexml \
#     xml \
#     opcache

# # Enable Apache modules
# RUN a2enmod rewrite headers

# # Configure Apache
# RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
#     && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
#     && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# # Configure PHP
# RUN echo "date.timezone = ${PHP_TIMEZONE:-UTC}" > /usr/local/etc/php/conf.d/timezone.ini \
#     && echo "upload_max_filesize = 50M" > /usr/local/etc/php/conf.d/uploads.ini \
#     && echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
#     && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/memory.ini \
#     && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/execution.ini \
#     && echo "session.gc_maxlifetime = 14400" >> /usr/local/etc/php/conf.d/session.ini \
#     && echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT" >> /usr/local/etc/php/conf.d/errors.ini \
#     && echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini \
#     && echo "log_errors = On" >> /usr/local/etc/php/conf.d/errors.ini

# # Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Set working directory
# WORKDIR /var/www/html

# # Create log directories
# RUN mkdir -p /var/www/html/protected/runtime \
#     && mkdir -p /var/www/html/assets \
#     && chmod -R 777 /var/www/html/protected/runtime \
#     && chmod -R 777 /var/www/html/assets

# # Fix permissions
# RUN chown -R www-data:www-data /var/www/html \
#     && chmod -R 755 /var/www/html

# # Expose port
# EXPOSE 80

# # Health check
# HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
#   CMD curl -f http://localhost/ || exit 1

# # Start Apache
# CMD ["apache2-foreground"]







# Use PHP 5.6 with Apache
# Use PHP 5.6 with Apache
FROM php:5.6-apache

# Install required extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd \
        --with-jpeg-dir=/usr/include/ \
        --with-freetype-dir=/usr/include/ \
    && docker-php-ext-install gd mysqli pdo pdo_mysql mbstring zip \
    && a2enmod rewrite

# Enable .htaccess overrides
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY ../ /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80












# FROM php:7.3-apache

# # Install PHP extensions
# RUN apt-get update && apt-get install -y wget \
#     && docker-php-ext-install pdo pdo_mysql mysqli \
#     && a2enmod rewrite

# # Download Yii 1.1.18 to /var/www/yii/
# RUN wget -q -O /tmp/yii.tar.gz https://github.com/yiisoft/yii/releases/download/1.1.18/yii-1.1.18.018a89.tar.gz \
#     && tar -xzf /tmp/yii.tar.gz -C /tmp \
#     && mv /tmp/yii-1.1.18.018a89/framework /var/www/yii \
#     && rm -rf /tmp/yii*

# # Fix get_magic_quotes_gpc() error
# RUN echo '<?php if (!function_exists("get_magic_quotes_gpc")) { function get_magic_quotes_gpc() { return false; } } ?>' > /var/www/html/fix.php

# # Create correct index.php
# RUN echo '<?php' > /var/www/html/index.php \
#     && echo '// Fix for PHP 7.4+' >> /var/www/html/index.php \
#     && echo 'require_once("fix.php");' >> /var/www/html/index.php \
#     && echo '' >> /var/www/html/index.php \
#     && echo '// Yii framework path' >> /var/www/html/index.php \
#     && echo '\$yii = "/var/www/yii/yii.php";' >> /var/www/html/index.php \
#     && echo 'defined("YII_DEBUG") or define("YII_DEBUG", true);' >> /var/www/html/index.php \
#     && echo 'defined("YII_TRACE_LEVEL") or define("YII_TRACE_LEVEL", 3);' >> /var/www/html/index.php \
#     && echo '' >> /var/www/html/index.php \
#     && echo 'require_once(\$yii);' >> /var/www/html/index.php \
#     && echo 'Yii::createWebApplication()->run();' >> /var/www/html/index.php

# # Set permissions
# RUN chown -R www-data:www-data /var/www

# EXPOSE 80
# CMD ["apache2-foreground"]