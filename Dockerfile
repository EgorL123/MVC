FROM php:7.4-apache
WORKDIR /var/www/html
RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug-3.1.3 && docker-php-ext-enable xdebug

EXPOSE 80