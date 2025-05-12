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
	done

	sleep 5
fi

exec docker-php-entrypoint "$@"