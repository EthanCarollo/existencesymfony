FROM dunglas/frankenphp

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV FRANKENPHP_PHP_OPCACHE_ENABLE=1
ENV SERVER_NAME=:80

WORKDIR /app

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    @composer \
    apcu \
    opcache

COPY composer.json composer.lock symfony.lock ./

RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative && \
    composer run-script --no-dev post-install-cmd

RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data var && \
    chmod -R 775 var

RUN php bin/console cache:clear --no-warmup && \
    php bin/console cache:warmup

RUN chown -R www-data:www-data /app

COPY <<EOF /etc/frankenphp/Caddyfile
{
    auto_https off
}

:80 {
    root * /app/public
    php_server
}
EOF

EXPOSE 80