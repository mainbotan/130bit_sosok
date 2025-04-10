FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    gd

RUN docker-php-ext-enable pdo_mysql
RUN a2enmod rewrite