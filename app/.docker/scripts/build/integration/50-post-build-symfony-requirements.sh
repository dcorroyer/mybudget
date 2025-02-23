#!/bin/sh

echo "Executing commands for Symfony/Composer requirements"

set -eux;

rm -rf var/**

mkdir -p var/cache var/log

composer dump-autoload --classmap-authoritative --no-dev
composer dump-env dev

chmod +x bin/console

php bin/console cache:clear
php bin/console assets:install

sync
