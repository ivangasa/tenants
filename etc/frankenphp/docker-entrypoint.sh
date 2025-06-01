#!/bin/bash
set -e

APP_NAMES="api"
SYMFONY_VERSION=${SYMFONY_VERSION:-}
STABILITY=${STABILITY:-stable}

if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then

	for APP in $APP_NAMES; do
		APP_DIR="apps/$APP"

		if [ ! -d "$APP_DIR" ]; then
			echo "Creating Symfony app in apps/$APP"
			mkdir -p "apps/$APP"
			composer create-project "symfony/skeleton $SYMFONY_VERSION" "apps/$APP" --stability="$STABILITY" --prefer-dist --no-progress --no-interaction --no-install || {
				echo "❌ Error creating Symfony app in apps/$APP"
				exit 1
			}
			echo "✅ $APP app ready in apps/$APP"

			cd "$APP_DIR" || exit 1

			composer config --no-interaction allow-plugins.runtime/frankenphp-symfony true
			composer require "php:>=$PHP_VERSION" runtime/frankenphp-symfony
			composer require symfony/runtime
			composer config --json extra.symfony.docker 'true'

			# setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX "${APP}/var"
			# setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX "${APP}/var"

			cd - > /dev/null
		fi
	done

	if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
    	composer install --prefer-dist --no-progress --no-interaction
    fi

	clear
	sleep 2
	for APP in $APP_NAMES; do
		APP_UPPERCASED=${APP^^}
		printf "\n\n"
		echo "#################################"
		echo " ⚠️  ${APP_UPPERCASED} app CONFIGURATION"
		php "./apps/$APP/bin/console" -V
		echo "	-> FrankenPHP worker mode: $FRANKENPHP_WORKER_CONFIG"
		echo "#################################"
		printf "\n"

		if grep -q ^${APP_UPPERCASED}_DATABASE_URL= .env; then
			echo "Waiting for ${APP_UPPERCASED} databases to be ready..."
			ATTEMPTS_LEFT_TO_REACH_DATABASE=60

			until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php apps/$APP/bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
				if [ $? -eq 255 ]; then
					# If the Doctrine command exits with 255, an unrecoverable error occurred
					ATTEMPTS_LEFT_TO_REACH_DATABASE=0
					break
				fi
				sleep 1
				ATTEMPTS_LEFT_TO_REACH_DATABASE=$((ATTEMPTS_LEFT_TO_REACH_DATABASE - 1))
				echo "Still waiting for database to be ready... Or maybe the database is not reachable. $ATTEMPTS_LEFT_TO_REACH_DATABASE attempts left."
			done

			if [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ]; then
				echo 'The database is not up or not reachable:'
				echo "$DATABASE_ERROR"
				exit 1
			else
				echo 'The database is now ready and reachable'
			fi

			#if find ./apps/$APP/src -type d -name Migrations | grep -q .; then
			#	php apps/$APP/bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing
			#fi
		fi
	done

	sleep 5
fi

exec docker-php-entrypoint "$@"