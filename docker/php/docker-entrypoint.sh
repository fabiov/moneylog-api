#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
    echo "entrypoint - setfacl"
    echo "$(whoami)"
	mkdir -p var/cache var/logs
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX .
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX .

    if [ -f composer.lock ]; then
        echo "entrypoint - composer"
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
		>&2 echo "Waiting for Postgres to be ready..."
		until pg_isready --timeout=0 --dbname="${DATABASE_URL}"; do
			sleep 1
		done
		bin/console doctrine:cache:clear-metadata
		bin/console doctrine:cache:clear-query
		bin/console doctrine:cache:clear-result
#		bin/console doctrine:migrations:migrate --no-interaction
#		bin/console doctrine:fixtures:load -n
	fi
fi

exec docker-php-entrypoint "$@"
