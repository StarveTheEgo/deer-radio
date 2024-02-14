#!/bin/sh

set -e

# Create key if necessary
if ! grep -q APP_KEY .env 2>/dev/null ; then
  php artisan key:generate --force
fi
php /var/www/app/artisan migrate --seed
php /var/www/app/artisan liquidsoap:user --if-not-exists
php /var/www/app/artisan liquidsoap:personal-token
php /var/www/app/artisan access-token:refresh

php-fpm -F
