# Use an official PHP image with FPM
FROM php:8.3-fpm

# Install necessary PHP extensions
RUN docker-php-ext-install mysqli

# Install any other dependencies if required
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Configure PHP-FPM to listen on port 9000
RUN sed -i 's/listen = \/run\/php\/php8.2-fpm.sock/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

# Copy application source code to the container
COPY . /var/www/html

# Copy and configure Nginx
COPY ./nginx/default.conf /etc/nginx/conf.d/

# Install Nginx
RUN apt-get update && apt-get install -y nginx

# Expose port 80 for Nginx and port 9000 for PHP-FPM
EXPOSE 80
EXPOSE 9000

# Start PHP-FPM and Nginx
CMD service nginx start && php-fpm
