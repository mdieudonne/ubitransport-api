# ubi-transport-api
##Installation
Prerequisite:
Docker Compose
`apt-get install docker docker-compose`

Clone the project and navigate to the project folder

`cd ubitransport-api;`

Run
`docker-compose build`
`docker-compose up -d`

Then, run

```
docker-compose run composer install;

docker-compose run php bin/console doctrine:database:create --if-not-exists;
docker-compose run php bin/console doctrine:database:create --env=test --if-not-exists;

docker-compose run php bin/console doctrine:schema:update --force;
docker-compose run php bin/console doctrine:schema:update --force --env=test;

docker-compose run php bin/console doctrine:fixtures:load --no-interaction;
docker-compose run php bin/console doctrine:fixtures:load --no-interaction --env=t
est;
```

##Testing
Run
`docker-compose run php bin/phpunit;`

## Usage
Use the dedicated Vue.js app ubitransport-app

Or Postman to test the routes

A documentation of the routes can be found at: api/doc

The document is partial, it describes routes, expected params only and return codes with a quick description only. To be continued.
