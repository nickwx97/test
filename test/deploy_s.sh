#!/usr/bin/env sh

set -x
docker run -d -p 9001:9001 --name my-apache-php-app -v /var/webapp/src:/var/www/html php:7.2-apache
sleep 1
set +x

echo 'Now...'
echo 'Visit http://localhost to see your PHP application in action.'