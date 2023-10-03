up:
	docker compose up -d

down:
	docker compose down

reup:
	docker compose down
	docker compose up -d

build:
	docker compose build

rebuild:
	docker compose build --no-cache

back:
	docker compose exec php bash

composer-install:
	docker compose run --rm php composer install

composer-update:
	docker compose run --rm php composer update

test:
	docker compose run --rm php vendor/bin/phpunit

inspect:
	docker compose run --rm php vendor/bin/grumphp run

cc:
	docker compose run --rm php bin/console c:c

clear:
	docker compose run --rm php artisan clear
	docker compose run --rm php artisan config:clear

dbcreate:
	docker compose run --rm php php bin/console d:d:c

dbupdate:
	docker compose run --rm php php bin/console doctrine:migrations:migrate

dbfixtures:
	docker compose run --rm php php bin/console doctrine:fixtures:load

dbrebuild:
	docker compose run --rm php php bin/console d:d:d --force
	docker compose run --rm php php bin/console d:d:c
	docker compose run --rm php php bin/console d:s:u --force

dbcreate-test:
	docker compose run --rm php php bin/console --env=test doctrine:database:create

dbupdate-test:
	docker compose run --rm php php bin/console --env=test doctrine:migrations:migrate

npm-install:
	docker compose run --rm npm install

npm-dev:
	docker compose run --rm npm run dev

npm-watch:
	docker compose run --rm npm run watch
