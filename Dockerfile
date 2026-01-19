FROM php:8.2-apache

# 1. Install System Dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql

# 2. Enable Apache Rewrite
RUN a2enmod rewrite

# 3. Copy Application Code
# We mimic the Hostinger structure: /var/www/core and /var/www/html
COPY core /var/www/core/
COPY public_html /var/www/html/

# 4. Set Permissions
RUN chown -R www-data:www-data /var/www/html /var/www/core

# 5. Environment Setup
# We need to tell Apache to serve from /var/www/html (Default is usually correct)
# But we ensure DocumentRoot is /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
