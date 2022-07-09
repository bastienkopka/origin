FROM php:7.4.30-fpm-alpine

ARG uname
ARG uid
ARG gid

ENV DRUSH_VERSION 8.4.10

RUN apk --update add --no-cache \
  sudo \
  bash \
  grep \
  zip \
  unzip \
  curl \
  wget \
  g++ \
  make \
  autoconf \
  shadow \
  git \
  patch \
  freetype-dev \
  libjpeg-turbo-dev \
  libpng-dev \
  libzip-dev \
  imagemagick-dev \
  icu-dev

RUN docker-php-ext-configure gd --with-jpeg --with-freetype

RUN docker-php-ext-install \
  gd \
  opcache \
  pdo_mysql

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
  && pecl install apcu redis\
  && docker-php-ext-enable apcu redis \
  && pecl clear-cache \
  && apk del .build-dependencies

# Add the user to the group.
RUN groupadd -g $gid -o $uname && \
  useradd -m -u $uid -g $gid -o -s /bin/sh $uname

# Composer.
COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/

# Drush.
RUN curl -fsSL -o /usr/local/bin/drush "https://github.com/drush-ops/drush/releases/download/$DRUSH_VERSION/drush.phar" && \
  chmod +x /usr/local/bin/drush

WORKDIR /var/www/web