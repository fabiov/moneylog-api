#!/bin/sh
set -e

echo "source /root/.bash_aliases" >> /root/.bashrc

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
    mkdir -p var/cache var/logs
    setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX .
    setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX .

    if [ -f composer.lock ]; then
        jwt_passphrase=$(grep '^JWT_PASSPHRASE=' .env | cut -f 2 -d '=')
        if [ ! -f config/jwt/private.pem ] || ! echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -noout > /dev/null 2>&1; then
            echo "Generating public / private keys for JWT"
            mkdir -p config/jwt
            echo "$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
            echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
            setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
            setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
        fi
    fi

    if [ "$APP_ENV" != 'prod' ]; then
        composer install --prefer-dist --no-progress --no-suggest --no-interaction
	  fi

    echo "Waiting for db to be ready..."
	  until bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
		    sleep 1
	  done

#	if [ "$APP_ENV" != 'prod' ]; then
#		bin/console doctrine:schema:update --force --no-interaction
#		bin/console doctrine:cache:clear-metadata
#		bin/console doctrine:cache:clear-query
#		bin/console doctrine:cache:clear-result
#		bin/console doctrine:migrations:migrate --no-interaction
#		bin/console doctrine:fixtures:load -n
#	fi
fi

exec docker-php-entrypoint "$@"
