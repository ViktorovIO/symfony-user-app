# Symfony User Application
Dockerized Symfony Application.

## Requirements:
You must have:
- [Docker](https://docs.docker.com/engine/install/);
- [Docker Compose](https://docs.docker.com/compose/install/#scenario-two-install-the-compose-plugin).

## For start:
1. Clone this repo on your computer;
2. Run command `make env`;
3. Open `.env` file and set your `POSTGRES_` credentials;
4. Run command `make up`;
5. Check that docker containers started;
6. Run command `make composer`;
7. Open a browser and go to the following address `http://localhost/`;
8. Enjoy!)


## Database initialize:
1. Check your `app/.env` file: it should have `DATABASE_URL`;
2. Run command `make db-create`.

## RabbitMQ admin panel:
- http://localhost:15672/
- Login (default): rabbitmq, Password (default): rabbitmq

## Local Email check:
- http://localhost:1025/

## Application paths:
- `http://localhost/` - Main page;
- `http://localhost/login` - Login page.

## API paths:
- [POST] `http://localhost/api/register` - Registration page;
- [GET] `http://localhost/api/login` - Login check page;
- [GET] `http://localhost/user/` - User list page;
- [GET, POST] `http://localhost/user/new` - Create new User page;
- [GET] `http://localhost/user/{userId}` - User show page;
- [GET, POST] `http://localhost/user/{userId}/edit` - Edit User page;
- [POST] `http://localhost/user/{id}` - Remove User page.

## TODO:
- [ ] Tests
- [ ] RESTful User API
- [ ] Make styles in twig templates
- [ ] Swagger documentation