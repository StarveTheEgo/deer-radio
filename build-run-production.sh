#!/bin/sh

docker-compose -f docker-compose-production.yml build
docker-compose -f docker-compose-production.yml up --no-deps -d

