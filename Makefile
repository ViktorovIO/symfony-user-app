DOCKER_COMPOSE = docker-compose -f ./docker-compose.yml --env-file ./.env
DOCKER_COMPOSE_PHP = docker-compose exec php-fpm

#############################
# DOCKER COMPOSE OPERATIONS #
#############################

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
	${DOCKER_COMPOSE_PHP} /bin/bash
