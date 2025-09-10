FROM php:8.3-fpm-alpine

# Instalar dependencias del sistema, incluyendo Nginx, y extensiones de PHP necesarias
# Añadimos 'apk update' para asegurarnos de que la lista de paquetes esté actualizada
RUN apk update && apk add --no-cache nginx \
    php83-pdo_mysql \
    php83-mysqli \
    php83-tokenizer \
    php83-xml \
    php83-mbstring \
    php83-ctype \
    php83-session \
    php83-dom \
    php83-json \
    php83-zip \
    php83-gd \
    php83-fileinfo \
    php83-opcache \
    php83-openssl \
    php83-exif \
    php83-iconv \
    php83-curl \
    php83-bcmath \
    php83-gmp \
    php83-intl \
    php83-pcntl \
    php83-posix \
    php83-gettext \
    php83-sodium \
    php83-redis \
    php83-pdo_pgsql

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD sh -c "nginx && php-fpm"
