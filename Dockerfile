# Resmi PHP 8.2 ve Apache sunucu imajını temel al
FROM php:8.2-apache

# Gerekli sistem paketlerini ve PHP eklentilerini kur
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev
RUN docker-php-ext-install mysqli zip

# Apache'nin "mod_rewrite" özelliğini aktif et
RUN a2enmod rewrite

# Composer'ı kur
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Çalışma dizinini ayarla
WORKDIR /var/www/html


COPY . /var/www/html/

RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf