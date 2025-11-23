FROM dunglas/frankenphp:1.1.0-php8.3

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libpq-dev \
    && docker-php-ext-install intl opcache zip pdo pdo_mysql

COPY . /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN echo 'root /app/public;' > /etc/frankenphp/conf.d/app.conf \
 && echo 'index index.php;' >> /etc/frankenphp/conf.d/app.conf \
 && echo 'php_entrypoint index.php;' >> /etc/frankenphp/conf.d/app.conf

EXPOSE 8080

CMD ["frankenphp", "run", "--config=/etc/frankenphp/conf.d/app.conf", "--worker"]
