FROM php:8.2-fpm-alpine

ARG USER_UID
ARG USER_GID

ARG COMPOSER_BIN=/usr/bin/composer
ARG COMPOSER_VERSION=2.2.12
ARG COMPOSER_SHA256SUM=1ce90687eb3f89a99c059d45dd419d08430ed249468544b932b1dad7fb22dda0

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

RUN set -ex ;\
  wget -O "$COMPOSER_BIN" "https://getcomposer.org/download/$COMPOSER_VERSION/composer.phar" ;\
  printf "%s  %s\n" "$COMPOSER_SHA256SUM" "$COMPOSER_BIN" | sha256sum -c - ;\
  chmod +x -- "$COMPOSER_BIN" ;\
  composer --version ;\
  composer diagnose || printf 'composer diagnose exited: %d\n' $? ;\
  :

USER deerdoor

WORKDIR /var/www/app

RUN chmod -R 775 ./storage && \
    chmod -R 775 /var/radio-storage && \
    mkdir -m 775 ./bootstrap/cache && \
    composer install && \
    npm ci && \
    npm run build

EXPOSE 9000

CMD ["/usr/bin/supervisord", "-c", "/usr/local/etc/supervisor/supervisord.conf"]
