FROM dunglas/frankenphp:latest-php8.2

WORKDIR /app

COPY . .

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    opcache \
    gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p config/jwt && \
    php bin/console lexik:jwt:generate-keypair --skip-if-exists || true

RUN chown -R www-data:www-data /app/var && \
    chmod -R 755 /app/var

ENV SERVER_NAME=:8080
ENV FRANKENPHP_CONFIG="worker ./public/index.php"

EXPOSE 8080

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]