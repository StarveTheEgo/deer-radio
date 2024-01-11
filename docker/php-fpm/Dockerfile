# Setting up PHP
FROM php:8.2-fpm-alpine

COPY ./docker/php-fpm/conf/php.ini /usr/local/etc/php/conf.d/local.ini
COPY ./docker/php-fpm/entrypoint.sh /var/www/entrypoint.sh

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk update && apk add --no-cache \
    build-base \
    bash \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    libxml2-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano \
    unzip \
    php82-tokenizer \
    php82-session \
    php82-xml \
    php82-xmlwriter \
    php82-dom \
    php82-pdo \
    php82-pdo_mysql \
    php82-fileinfo \
    git \
    oniguruma-dev \
    curl  \
    composer && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd bcmath pdo_mysql mbstring zip exif pcntl -j$(nproc) && \
    apk add --update nodejs npm && \
    npm install npm@latest -g && \
    npm install n -g && \
    n latest && \
    git config --global user.email "deerfuldeeringdeer@gmail.com" && \
    git config --global user.name "EvergreenDeer"

RUN addgroup -g 1000 deerdoor && adduser -u 1000 -G deerdoor -S deerdoor

COPY --chown=deerdoor:deerdoor . /var/www/app

USER deerdoor

WORKDIR /var/www/app

RUN chmod -R 775 ./storage && \
    mkdir -m 755 ./bootstrap/cache && \
#    chmod -R 755 ./bootstrap/cache && \
    composer install && \
    npm ci && \
    npm run build

EXPOSE 9000

ENTRYPOINT ["/var/www/entrypoint.sh", "php-fpm", "-F"]
