#!/bin/sh

set -e

php artisan migrate --seed

exec "$@"
