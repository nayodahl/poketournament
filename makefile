SHELL := /bin/bash

tests:
	APP_ENV=test php bin/console doctrine:fixtures:load -n
	php bin/phpunit
.PHONY: tests

phpstan:
	php vendor/bin/phpstan analyse -c phpstan.neon
.PHONY: phpstan