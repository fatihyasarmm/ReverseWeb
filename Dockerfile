# Resmi PHP 8.2 ve Apache sunucu imajını temel al
FROM php:8.2-apache

# Gerekli sistem paketlerini (git, zip) ve PHP eklentilerini kur
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev
RUN docker-php-ext-install mysqli zip

# Apache'nin "mod_rewrite" özelliğini aktif et
RUN a2enmod rewrite


ENV APACHE_DOCUMENT_ROOT /var/www/html/fatihprograms/webproje
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


WORKDIR /var/www/html