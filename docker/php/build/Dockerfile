FROM php:7.1.3-fpm-alpine

RUN set -ex \
    && apk add --no-cache git zlib-dev icu-dev freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev \
    && docker-php-ext-configure gd \
        --with-gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
      && NPROC=$(getconf _NPROCESSORS_ONLN) \
    && docker-php-ext-install -j${NPROC} gd intl zip mysqli pdo pdo_mysql \
    && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["php-fpm"]