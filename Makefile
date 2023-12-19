SHELL := /bin/bash
DOCKER_COMPOSE = docker compose -p poke
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
	$(DB) mysql -usymfony -psymfony -e 'DROP DATABASE IF EXISTS poke; CREATE DATABASE poke;'
	$(DB) mysql -usymfony -psymfony poke < infra/import/poketournament.sql

composer-install:
	$(WEB) bash -c "composer install -n --prefer-dist --no-interaction --working-dir=/var/www/poke"

composer-outdated:
	$(WEB) bash -c "composer outdated --working-dir=/var/www/poke"

composer-update:
	$(WEB) bash -c "composer update -n --prefer-dist --no-interaction --working-dir=/var/www/poke"

yarn-install: ## install yarn dependencies
	@$(WEB) bash -c "yarn install --frozen-lockfile"

yarn-upgrade:
	@$(WEB) bash -c "yarn upgrade"

webpack-deploy: ## build assets with webpack
	@$(WEB) bash -c "yarn encore dev"

webpack-watch: ## hot reload
	@$(WEB) bash -c "yarn encore dev --watch"

test-init: ## initialize test database with fixtures
	@$(WEB) bash -c "bin/console doctrine:database:create --if-not-exists --env test"
	@$(WEB) bash -c "bin/console doctrine:schema:drop --full-database --force --env test"
	@$(WEB) bash -c "bin/console doctrine:schema:update -f --env test"

test:
	$(WEB) bash -c "bin/console doctrine:fixtures:load -n --env test && php bin/phpunit"

phpstan:
	$(WEB) bash -c "php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit 1G"

phpcs:
	$(WEB) bash -c "./vendor/bin/phpcs src/"

phpcbf:
	$(WEB) bash -c "./vendor/bin/phpcbf src/"

init-filesystem: ## create necessary files/folder for installation
	[ -d ~/.composer ] || mkdir ~/.composer

init-tls: ## install mkcert and generate certificates for local https
	@sudo apt install libnss3-tools
	@sudo wget -O /usr/local/bin/mkcert https://github.com/FiloSottile/mkcert/releases/download/v1.4.1/mkcert-v1.4.1-linux-amd64
	@sudo chmod +x /usr/local/bin/mkcert
	@mkcert -install
	@mkcert --cert-file ./infra/certs/localhost.crt --key-file ./infra/certs/localhost.key localhost haproxy 127.0.0.1 ::1
	@cat ./infra/certs/localhost.key ./infra/certs/localhost.crt > ./infra/certs/server.pem
	@cp "$$(mkcert -CAROOT)/rootCA.pem" ./infra/certs/localCA.crt

install: init-filesystem init-tls build start yarn-install webpack-deploy composer-install ## setup a new dev environment from scratch
