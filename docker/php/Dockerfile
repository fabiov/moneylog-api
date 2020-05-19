ARG PHP_VERSION=7.3

FROM php:${PHP_VERSION}-fpm AS api_php

RUN apt-get update
RUN apt-get install -y git net-tools openssh-server supervisor unzip vim acl rsync libpq-dev \
    && docker-php-ext-install pdo pdo_mysql

ARG APCU_VERSION=5.1.17
RUN set -eux; \
	pecl install apcu-${APCU_VERSION} xdebug; \
	pecl clear-cache; \
	docker-php-ext-enable apcu opcache xdebug; \
#	echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
#	; \
    echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
	; \
    echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
COPY ./docker/php/bash_aliases /root/.bash_aliases

ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
	composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY docker/php/.ssh /root/.ssh
RUN chmod 600 /root/.ssh/id_rsa*

WORKDIR /srv/api

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.json composer.lock ./
#RUN set -eux; \
#	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest; \
#	composer clear-cache

COPY . ./

RUN set -eux; \
#	mkdir -p var/cache var/log; \
#	composer dump-autoload --classmap-authoritative --no-dev; \
#	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync
VOLUME /srv/api/var

COPY ./docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
