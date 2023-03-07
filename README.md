# Symfony User Application
Dockerized Symfony Application.

## Requirements:
You must have:
- Docker;
- Docker Compose.

## For start:
1. Clone this repo on your computer;
2. Run command `make env`;
3. Open `.env` file and set your `POSTGRES_` credentials;
4. Run command `make up`;
5. Check that docker containers started;
6. Run command `make composer`;
7. Open a browser and go to the following address `http://127.0.0.1/`;
8. Enjoy!)


## Database initialize:
1. Check your `app/.env` file: it should have `DATABASE_URL`;
2. Run command `make db-create`.

## RabbitMQ admin panel:
- http://localhost:15672/
- Login (default): rabbitmq, Password (default): rabbitmq

## Local Email check:
- http://127.0.0.1:1025/