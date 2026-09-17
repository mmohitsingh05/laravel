# syntax=docker/dockerfile:1

# Stage 1 - Build frontend assets (Vite)
FROM node:22 AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2 - Backend (Laravel + Apache + PHP)
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        git curl unzip libonig-dev libzip-dev libpq-dev libsqlite3-dev zip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pdo_sqlite mbstring zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable rewrite + serve Laravel from the public/ directory
RUN a2enmod rewrite \
    && printf '%s\n' \
        '<VirtualHost *:80>' \
        '    ServerName localhost' \
        '    DocumentRoot /var/www/html/public' \
        '' \
        '    <Directory /var/www/html/public>' \
        '        Options Indexes FollowSymLinks' \
        '        AllowOverride All' \
        '        Require all granted' \
        '    </Directory>' \
        '' \
        '    ErrorLog ${APACHE_LOG_DIR}/error.log' \
        '    CustomLog ${APACHE_LOG_DIR}/access.log combined' \
        '</VirtualHost>' \
        > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built frontend assets from Stage 1
COPY --from=frontend /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Fix permissions for Laravel's writable directories
RUN chown -R www-data:www-data storage bootstrap/cache

# Startup script: binds Apache to $PORT and runs migrations
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
