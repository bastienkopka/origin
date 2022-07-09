UID=$$(id -u $$USER)
GID=$$(id -g $$USER)
UNAME=$$(whoami)

SUPPORTED_COMMANDS := docker-init new-project docker-compile-assets
SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
	COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
	ifeq (, $(COMMAND_ARGS))
		COMMAND_ARGS := dev
	endif
endif

# Include .env file
ifneq ("$(wildcard ./.env)","")
	include ./.env
else
	include ./.env.$(COMMAND_ARGS)
endif

DATABASE_URL := mysql://$(MYSQL_USER):$(MYSQL_PASSWORD)@$(MYSQL_DRIVER)/$(MYSQL_DATABASE)

docker-init:
	cp ./.env.$(COMMAND_ARGS) ./.env
	@echo "LOCAL_UID=$(UID)" >> .env
	@echo "LOCAL_GID=$(GID)" >> .env
	@echo "UNAME=$(UNAME)" >> .env
	cp ./docker-compose.$(COMMAND_ARGS).yml ./docker-compose.yml

docker-up:
	docker compose up -d --build

docker-stop:
	docker compose stop

#### Global commands.
composer-install:
	docker compose exec -u $(DOCKER_USERNAME) php composer install

drush-install:
	docker compose exec -u $(DOCKER_USERNAME) php drush si $(SITE_PROFILE) -y --db-url=$(DATABASE_URL) \
		--site-name="$(SITE_NAME)" --account-name="$(SITE_USER)" --account-mail="$(SITE_EMAIL)" \
		--account-pass="$(SITE_PASSWORD)" --site-mail="$(SITE_EMAIL)";
	./scripts/install.sh

drush-cr:
	docker compose exec -u $(DOCKER_USERNAME) php drush cr

new-project: docker-init docker-up composer-install drush-install init-assets

init-assets:
	docker compose exec node sh -c 'cd /var/www/web/conf/assets && npm install'

compile-assets-dev:
	docker compose exec node sh -c 'cd /var/www/web/conf/assets && npm run dev'

compile-assets-prod:
	docker compose exec node sh -c 'cd /var/www/web/conf/assets && npm run prod'

#### Docker commands.
docker-shell-web:
	docker compose exec -u $(DOCKER_USERNAME) php sh

#### Quality commands.
quality-phpcs:
	@docker compose exec -u $(DOCKER_USERNAME) php ./vendor/bin/phpcs \
		--standard=./scripts/quality/phpcs/phpcs.xml.dist

quality-phpstan:
	docker compose exec -u $(DOCKER_USERNAME) php ./vendor/bin/phpstan \
		analyse \
		--configuration ./scripts/quality/phpstan/phpstan.neon

quality-phpmd:
	docker compose exec -u $(DOCKER_USERNAME) php ./vendor/bin/phpmd \
		./app/modules/custom,./app/themes/custom \
		ansi \
		./scripts/quality/phpmd/phpmd.xml --suffixes inc,info,install,module,php,test,theme

quality-rector:
	docker compose exec -u $(DOCKER_USERNAME) php ./vendor/bin/rector \
		process \
		app/modules/custom \
		app/profiles/custom \
		app/themes/custom \
		--dry-run \
		--config=./scripts/quality/rector/rector.php

#### Tests commands.
test-phpunit:
	docker compose exec php bash ./scripts/tests/phpunit/run-phpunit.sh
