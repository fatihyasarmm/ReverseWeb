# Resmi PHP 8.2 ve Apache sunucu imajını temel al
FROM php:8.2-apache

# Gerekli sistem paketlerini (git, zip) ve PHP eklentilerini kur
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev
RUN docker-php-ext-install mysqli zip

# Apache'nin "mod_rewrite" özelliğini aktif et (güzel URL'ler için)
RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html