#!/bin/bash

set -ex

rm -rf ./docker/dev-certs/deer-radio/*

docker run -v ./docker/dev-certs/deer-radio:/certs -e SERVER_HOSTNAMES="${APP_HOST}" -it nmasse/mkcert:latest

cp ./docker/dev-certs/deer-radio/server.crt  ./docker/dev-certs/deer-radio/fullchain.pem
cp ./docker/dev-certs/deer-radio/server.key  ./docker/dev-certs/deer-radio/privkey.pem
