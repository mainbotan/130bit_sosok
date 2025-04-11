FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_pgsql \
    zip \
    gd

RUN a2enmod rewrite
