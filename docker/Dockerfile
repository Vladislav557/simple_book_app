FROM php:8.2-fpm-alpine

RUN ln -sf /usr/share/zoneinfo/Europe/Moscow /etc/localtime

RUN apk add --update linux-headers

RUN apk add autoconf \
    build-base \
    unzip \
    libzip-dev \
    git \
    freetype \
    freetype-dev \
    postgresql-dev

RUN rm  /usr/local/etc/php-fpm.d/* \
    && wget https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions -O /usr/local/bin/install-php-ext \
    && chmod +x /usr/local/bin/install-php-ext

COPY ./docker/symfony.pool.conf /usr/local/etc/php-fpm.d/
COPY ./docker/php.ini /usr/local/etc/php/conf.d/default.ini

RUN pecl channel-update pecl.php.net

RUN docker-php-ext-install pdo pdo_mysql intl gd zip

RUN apk add bash mc nginx supervisor \
    && chmod -R 0777 /var/tmp/ \
    && chmod -R 0777 /var/lib/nginx \
    && rm -f /etc/nginx/conf.d/default.conf \
    && mkdir /etc/nginx/conf.d \
    && echo "upstream php-upstream { server localhost:9001; }" > /etc/nginx/conf.d/upstream.conf

ADD ./docker/nginx.conf /etc/nginx/
ADD ./docker/symfony.conf /etc/nginx/conf.d

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY ./symfony /var/www/symfony
WORKDIR /var/www/symfony
RUN php composer.phar install -vvv && chmod -R 0777 /var/www/symfony && chown -R www-data:www-data /var/www/symfony

ADD ./docker/supervisord.conf /etc/
ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]