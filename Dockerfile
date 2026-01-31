FROM php:8.4-fpm-alpine

# Install system deps
RUN apk add --no-cache \
    nginx supervisor curl git unzip \
    nodejs npm \
    libpng-dev oniguruma-dev libxml2-dev

# PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring bcmath gd opcache

RUN mkdir -p /var/run/php && chown -R www-data:www-data /var/run/php

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nginx
COPY nginx/nginx.conf /etc/nginx/nginx.conf

# Laravel
COPY laravel /var/www/laravel
WORKDIR /var/www/laravel
RUN composer install --no-dev --optimize-autoloader \
 && chown -R www-data:www-data /var/www/laravel

# Nuxt
COPY nuxt /var/www/nuxt
WORKDIR /var/www/nuxt
RUN npm install && npm run build

# HLS storage (Railway volume)
RUN mkdir -p /data/hls \
 && chown -R www-data:www-data /data

# Supervisor
COPY supervisord.conf /etc/supervisord.conf

EXPOSE 8080
CMD ["supervisord","-c","/etc/supervisord.conf"]

