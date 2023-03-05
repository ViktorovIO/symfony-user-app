DOCKER_COMPOSE = docker-compose -f ./docker-compose.yml --env-file ./.env
DOCKER_COMPOSE_PHP = docker-compose exec php-fpm

#############################
# DOCKER COMPOSE OPERATIONS #
#############################

env:
	cp .env.example .env && \
	cp ./app/.env.dev ./app/.env

up:
	${DOCKER_COMPOSE} up -d --build

down:
	${DOCKER_COMPOSE} down

restart:
	${DOCKER_COMPOSE} restart


###############
# APPLICATION #
###############

php:
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm bash

composer:
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm composer install

jwt:
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm bin/console lexik:jwt:generate-keypair

cache-clear:
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm bin/console cache:clear

rebuild: cache-clear down up \
	@echo "rebuilded"

db-create:
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm bin/console doctrine:database:create