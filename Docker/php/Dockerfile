FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get -y update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug

COPY php/conf.d/* $PHP_INI_DIR/conf.d

CMD ["php-fpm"]