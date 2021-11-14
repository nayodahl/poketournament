SHELL := /bin/bash
DOCKER_COMPOSE = docker-compose -p poke
WEB = docker exec -i web
DB = docker exec -i database

build:
	$(DOCKER_COMPOSE) build

start:
	$(DOCKER_COMPOSE) up --remove-orphans -d

pull:
	$(DOCKER_COMPOSE) pull

kill:
	$(DOCKER_COMPOSE) kill || true

rm: kill
	@$(DOCKER_COMPOSE) rm --force || true

db-init:
	$(DB) mysql -usymfony -psymfony poke < infra/import/poketournament.sql

composer-install:
	$(WEB) bash -c "composer install -n --prefer-dist --no-interaction --working-dir=/var/www/poke"

composer-outdated:
	$(WEB) bash -c "composer outdated --working-dir=/var/www/poke"

composer-update:
	$(WEB) bash -c "composer update -n --prefer-dist --no-interaction --working-dir=/var/www/poke"

yarn-install: ## install yarn dependencies
	@$(WEB) bash -c "cd poke && yarn install --frozen-lockfile"

yarn-upgrade:
	@$(WEB) bash -c "cd poke && yarn upgrade"

webpack-deploy: ## build assets with webpack
	@$(WEB) bash -c "cd poke && yarn encore dev"

test-init: ## initialize test database with fixtures
	@$(WEB) bash -c "cd poke && DB_DISCRIMINATOR=_bkp && bin/console doctrine:database:create --if-not-exists --env test"
	@$(WEB) bash -c "cd poke && DB_DISCRIMINATOR=_bkp && bin/console doctrine:schema:drop --full-database --force --env test"
	@$(WEB) bash -c "cd poke && DB_DISCRIMINATOR=_bkp && bin/console doctrine:schema:update -f --env test"

test:
	$(WEB) bash -c "cd poke && APP_ENV=test && php bin/console doctrine:fixtures:load -n && php bin/phpunit"

phpstan:
	$(WEB) bash -c "cd poke && php vendor/bin/phpstan analyse -c phpstan.neon"