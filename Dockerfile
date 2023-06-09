FROM php:7.4-fpm AS base

ARG USER_ID=1000
ARG GROUP_ID=1000
ARG APP_ENV=dev
ENV APP_ENV=$APP_ENV

RUN apt-get update && apt-get install -y --no-install-recommends \
    apt-utils \
    zip \
    unzip \
    ssh \
    g++ \
    git \
    curl \
    libcurl4-gnutls-dev \
    libpq-dev \
    libicu-dev

RUN docker-php-ext-install \
    intl \
    curl \
    bcmath \
    gettext \
    pdo_pgsql


RUN pecl install apcu \
  && docker-php-ext-enable apcu

RUN docker-php-ext-install opcache

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear

RUN docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

WORKDIR /app

FROM base as dev

RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && touch /var/log/xdebug.log \
    && chown www-data:www-data /var/log/xdebug.log \
    && chmod 666 /var/log/xdebug.log

CMD php-fpm -F

FROM dev as test

COPY ./ /app

RUN composer install

FROM base as prod

COPY ./ /app

RUN usermod -u ${USER_ID} www-data
RUN chown -R www-data:www-data /app

USER "${USER_ID}:${GROUP_ID}"

RUN composer install --no-dev --prefer-dist --no-progress --optimize-autoloader
