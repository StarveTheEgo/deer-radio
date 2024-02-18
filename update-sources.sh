#!/bin/sh

# This is a handy script that you will probably never use

# Update only app subdirectory of Laravel app (we want to exclude big vendor directory, but later someday)
docker cp ./src/app/ deer-radio-php-fpm:/var/www/app/app/

# Update entire liquidsoap app
docker cp ./docker/liquidsoap/radio/ deer-radio-liquidsoap:/var/liquidsoap/radio
