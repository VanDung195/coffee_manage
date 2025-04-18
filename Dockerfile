FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache nodejs npm

COPY . .

RUN cp .env.example .env \
    && composer install --no-dev --optimize-autoloader \
    && npm ci \
    && npm run build \
    && php artisan key:generate \
