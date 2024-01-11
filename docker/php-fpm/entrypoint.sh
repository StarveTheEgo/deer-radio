#!/bin/sh

set -e

php /var/www/app/artisan migrate --seed

exec "$@"
