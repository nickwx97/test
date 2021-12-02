FROM php:7.2-apache
COPY src/ /var/www/html/
RUN apt update && apt install -y git unzip
RUN chown -R www-data:www-data ../html && chmod -R 755 ../html
