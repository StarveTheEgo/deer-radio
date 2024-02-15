#!/bin/sh

set -e

php /var/www/app/artisan migrate --seed --force
php /var/www/app/artisan liquidsoap:user --if-not-exists
php /var/www/app/artisan liquidsoap:personal-token
php /var/www/app/artisan access-token:refresh

php-fpm -F
