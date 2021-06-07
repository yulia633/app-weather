FROM php:8.0.5

RUN docker-php-source extract && docker-php-ext-install mysqli && docker-php-source delete

RUN apt-get update && apt-get install -y curl git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY database/install_db.sql /docker-entrypoint-initdb.d/
