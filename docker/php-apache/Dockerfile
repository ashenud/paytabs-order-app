FROM php:8.4-apache

LABEL maintainer="Ashen Udithamal <udithamal.lk@gmail.com>"

# Enable Apache rewrite module (useful for routing if needed)
RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    curl

# Install mysqli extension and any required libraries
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer