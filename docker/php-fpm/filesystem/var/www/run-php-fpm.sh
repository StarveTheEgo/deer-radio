#!/bin/sh

set -e

php /var/www/app/artisan migrate --seed
php /var/www/app/artisan liquidsoap:user --if-not-exists
php /var/www/app/artisan liquidsoap:personal-token

php-fpm -F
