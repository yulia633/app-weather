FROM php:apache

RUN docker-php-source extract && docker-php-ext-install mysqli && docker-php-source delete

RUN apt-get update && apt-get install -y curl
