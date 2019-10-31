ARG PHP_VERSION=7.2

FROM php:${PHP_VERSION}-fpm AS api_php

# persistent / runtime deps
RUN apt-get update; \
	apt-get upgrade; \
	apt-get autoremove; \
	apt-get install -y acl file gettext git libzip-dev rsync;

RUN set -eux; \
	docker-php-ext-install zip;

RUN set -eux; \
	pecl install apcu-5.1.17 xdebug; \
	pecl clear-cache; \
	echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini; \
    echo "xdebug.remote_autostart=on" >> /usr/local/etc/php/conf.d/xdebug.ini; \
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)";

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN set -eux; \
	composer global require "hirak/prestissimo:^0.3" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /var/www/moneylog-api

# prevent the reinstallation of vendors at every changes in the source code
#COPY composer.json composer.lock ./
#RUN set -eux; \
#	composer install --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress --no-suggest; \
#	composer clear-cache

COPY . ./

#RUN set -eux; \
#	chmod +x bin/console; sync
VOLUME /var/www/moneylog-api/var

COPY ./docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
