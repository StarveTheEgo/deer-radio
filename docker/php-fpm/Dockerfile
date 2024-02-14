FROM php:8.2-fpm-alpine

ARG USER_UID
ARG USER_GID

COPY ./docker/php-fpm/filesystem/ /
COPY ./src/. /var/www/app
COPY ./.env /var/www/.env

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
    php82-bcmath \
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
    composer \
    supercronic \
    supervisor && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd bcmath pdo_mysql mbstring zip exif pcntl -j$(nproc) && \
    apk add --update nodejs npm && \
    npm install npm@latest -g && \
    npm install n -g && \
    n latest && \
    addgroup -g "${USER_GID}" deerdoor && adduser -u "${USER_UID}" -G deerdoor -S deerdoor && \
    chown -R deerdoor:deerdoor /var/www/app && \
    chown -R deerdoor:deerdoor /var/www/.env && \
    chown -R deerdoor:deerdoor /var/www/run-php-fpm.sh && \
    chmod +x /var/www/run-php-fpm.sh && \
    # configuring upcoming radio-storage shared volume
    chown -R deerdoor:deerdoor /var/radio-storage && \
    rm -rf /tmp/* /var/cache/apk/*

USER deerdoor

WORKDIR /var/www/app

RUN chmod -R 775 ./storage && \
    chmod -R 775 /var/radio-storage && \
    mkdir -m 775 ./bootstrap/cache && \
    composer install && \
    npm ci && \
    npm run build

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
