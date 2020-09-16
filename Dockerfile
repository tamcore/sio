FROM php:7.2-apache
VOLUME /database
COPY . /var/www/html/
