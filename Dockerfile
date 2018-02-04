FROM php:7.0-apache

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql
RUN apt-get install -y vim netcat
RUN a2enmod rewrite

COPY config/000-default.conf /etc/apache2/sites-available/000-default.conf