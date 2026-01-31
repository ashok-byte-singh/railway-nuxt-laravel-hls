FROM alpine:3.20

# Base packages
RUN apk add --no-cache \
    nginx supervisor curl git unzip \
    nodejs npm \
    php84 php84-fpm php84-cli \
    php84-pdo php84-pdo_mysql php84-mbstring \
    php84-bcmath php84-gd php84-opcache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Nginx
COPY nginx/nginx.conf /etc/nginx/nginx.conf

# Laravel
COPY laravel /var/www/laravel
WORKDIR /var/www/laravel
RUN composer install --no-dev --optimize-autoloader \
 && chown -R nginx:nginx /var/www/laravel

# Nuxt 4
COPY nuxt /var/www/nuxt
WORKDIR /var/www/nuxt
RUN npm install \
 && npm run build

# HLS storage (Railway volume)
RUN mkdir -p /data/hls \
 && chown -R nginx:nginx /data

# Supervisor
COPY supervisord.conf /etc/supervisord.conf

EXPOSE 8080
CMD ["/usr/bin/supervisord","-c","/etc/supervisord.conf"]

