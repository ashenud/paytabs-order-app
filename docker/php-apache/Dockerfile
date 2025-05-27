FROM php:8.4-apache

LABEL maintainer="Ashen Udithamal <udithamal.lk@gmail.com>"

# Enable Apache rewrite module (useful for routing if needed)
RUN a2enmod rewrite

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql
