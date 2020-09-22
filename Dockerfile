FROM php:7.4-apache

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

RUN echo "Listen 8080" > /etc/apache2/ports.conf

VOLUME /database
COPY . /var/www/html/
