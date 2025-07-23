FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-install intl mysqli \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* 