FROM php:8.2.12-apache

COPY . /var/www/html

RUN docker-php-ext-install pdo_mysql mysqli

CMD ["apache2-foreground"]