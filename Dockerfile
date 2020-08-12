# ---- PHP 7 ----
FROM php:7.2-fpm

LABEL maintainer="Joseph Ian Farillas <jfarillas@gmail.com>"

#Install git
RUN apt-get update \
    && apt-get install -y sudo \
    && apt-get install -y systemd \
    && apt-get install -y iputils-ping \
    && apt-get install -y net-tools \
    && apt-get install -y git \
    && apt-get install -y libpng-dev \
    && apt-get install -y libicu-dev \
    && apt-get install -y libxml2-dev \
    && apt-get install -y libzip-dev
RUN docker-php-ext-install pdo pdo_mysql mysqli bcmath gd intl xml hash zip dom session opcache
# RUN a2enmod rewrite
# Install Composer
RUN apt-get install -y curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PPA
RUN apt-get install -y software-properties-common

# Go to the current direcory
WORKDIR /usr/src/api

# Copy host directory to the Docker directory
COPY . .

# Install app dependencies
COPY composer.json .

# These following steps are intended on local development only
# Install Laravel dependencies
RUN composer install \
    && composer dump-autoload

# Run DB migration script
RUN php artisan migrate

# Run DB seeder script (For role permissions)
RUN php artisan db:seed --class=PermissionTableSeeder
