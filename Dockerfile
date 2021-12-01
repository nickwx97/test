FROM php:7.2-apache
COPY src/ /var/www/html/
COPY php.ini-production /var/www/html/
COPY .htaccess /var/www/html/
RUN apt update && apt install -y git unzip
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN chown -R www-data:www-data ../html && chmod -R 755 ../html
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"
RUN composer require sonata-project/google-authenticator
RUN composer require endroid/qr-code
RUN composer require phpunit/phpunit
RUN rm /var/www/html/vendor/sonata-project/google-authenticator/src/GoogleAuthenticator.php && cp /var/www/html/overwrite/GoogleAuthenticator.php /var/www/html/vendor/sonata-project/google-authenticator/src/