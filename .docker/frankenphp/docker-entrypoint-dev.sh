#!/bin/sh
set -e

if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	# if composer.json is present, install the project
	if [ -f composer.json ]; then
	  composer install --prefer-dist --no-progress --no-interaction || true # Always install because in dev some packages are not installed in branch switching
	fi

  if -f .env; then # prevent check if no .env exists for the moment
    if grep -q ^DATABASE_URL= .env; then
      echo "Waiting for database to be ready..."
      ATTEMPTS_LEFT_TO_REACH_DATABASE=60
      until [ $ATTEMPTS_LEFT_TO_REACH_DATABASE -eq 0 ] || DATABASE_ERROR=$(php bin/console dbal:run-sql -q "SELECT 1" 2>&1); do
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
        echo "The database is not up or not reachable:"
        echo "$DATABASE_ERROR"
        exit 1
      else
        echo "The database is now ready and reachable"
      fi

      if [ "$( find ./migrations -iname '*.php' -print -quit )" ]; then
        php bin/console doctrine:migrations:migrate --no-interaction --all-or-nothing
      fi
    fi
  fi

  mkdir -p /home/www-data/.config/caddy
	setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var /data /config /app /tools /home/www-data/.config/caddy
	setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var /data /config /app /tools /home/www-data/.config/caddy
fi

chmod +x /tools/bin/* || true
ln -s /tools/bin/* /usr/local/bin/ || true

# Add /tools/bin to PATH
export PATH=$PATH:/tools/bin

gosu 'www-data:www-data' docker-php-entrypoint "$@"
