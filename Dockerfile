FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx supervisor curl git unzip \
    nodejs-current npm \
    libpng-dev oniguruma-dev libxml2-dev

RUN docker-php-ext-install pdo_mysql mbstring bcmath gd opcache

# Force PHP-FPM TCP
COPY laravel/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
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

COPY supervisord.conf /etc/supervisord.conf

EXPOSE 8080
CMD ["supervisord","-c","/etc/supervisord.conf"]

