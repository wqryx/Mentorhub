FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx \
    php8-pdo_mysql \
    php8-mysqli \
    php8-tokenizer \
    php8-xml \
    php8-mbstring \
    php8-ctype \
    php8-session \
    php8-dom \
    php8-json \
    php8-zip \
    php8-gd \
    php8-fileinfo \
    php8-opcache \
    php8-openssl \
    php8-exif \
    php8-iconv \
    php8-curl \
    php8-bcmath \
    php8-gmp \
    php8-intl \
    php8-pcntl \
    php8-posix \
    php8-gettext \
    php8-xdebug \
    php8-sodium \
    php8-redis \
    php8-pdo_pgsql # Para PostgreSQL, si lo usas

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD sh -c "nginx && php-fpm"
