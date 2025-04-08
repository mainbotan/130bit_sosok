FROM php:8.2-apache

# Установка необходимых зависимостей и расширений
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    gd

# Включите mod_rewrite для Apache
RUN a2enmod rewrite

# Убедитесь, что расширения активированы
RUN docker-php-ext-enable pdo_mysql