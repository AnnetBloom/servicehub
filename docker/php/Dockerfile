FROM composer:2 AS composer

FROM php:8.5-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# 1. Указываем рабочую директорию
#WORKDIR /var/www/html

# 2. Копируем исходный код проекта СРАЗУ с назначением владельца www-data
# (Это критически важно для продакшна, когда код вшивается в образ)
#COPY --chown=www-data:www-data . .

# 3. Переключаем контейнер на работу от имени пользователя www-data вместо root
#USER www-data
