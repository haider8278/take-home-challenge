# Use a node base image to install dependencies for React
FROM node:14 as node_builder

# Set working directory for React
WORKDIR /app

# Copy the React app's package.json and install dependencies
COPY ./frontend/package*.json ./
RUN npm install

# Copy all React app files and build the app
COPY ./frontend/ ./
RUN npm run build

# Use php-fpm base image for Laravel
FROM php:8.0-fpm

# Install necessary packages for Laravel and PHP
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    nginx \
    supervisor

# Install required PHP extensions for Laravel
RUN docker-php-ext-install pdo_mysql

# Set working directory for Laravel
WORKDIR /var/www

# Copy all Laravel files
COPY ./backend /var/www

# Copy the React app build files from the node stage to the Laravel public folder
COPY --from=node_builder /app/build /var/www/public

# Copy Nginx configuration
COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf

# Install Composer and Laravel dependencies
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy supervisor config for Nginx and PHP-FPM
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80 for Nginx
EXPOSE 80

# Start both Nginx and PHP-FPM with Supervisor
CMD ["/usr/bin/supervisord"]
