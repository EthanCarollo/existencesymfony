FROM composer:2 AS composer_stage

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative


FROM dunglas/frankenphp:1.1.0-php8.3

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpq-dev \
    && docker-php-ext-install intl opcache zip pdo pdo_mysql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=composer_stage /app /app

ENV APP_ENV=prod \
    APP_DEBUG=0 \
    PHP_OPCACHE_ENABLE=1

RUN mkdir -p var/cache var/log config/jwt \
    && chown -R www-data:www-data var \
    && chmod -R 775 var

RUN php bin/console cache:clear --env=prod --no-debug \
    && php bin/console cache:warmup --env=prod --no-debug

RUN echo 'root /app/public;' > /etc/frankenphp/conf.d/app.conf \
    && echo 'encode gzip zstd;' >> /etc/frankenphp/conf.d/app.conf \
    && echo 'php_server;' >> /etc/frankenphp/conf.d/app.conf

EXPOSE 8080

USER www-data

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]