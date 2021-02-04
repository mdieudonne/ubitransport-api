# ubi-transport-api
##Installation
Clone the project and navigate to the project folder

`cd ubitransport-api;`

Run

`composer install;`

Run

`docker-compose up -d`
and wait until containers are started

Then run:
```php bin/console doctrine:database:create --if-not-exists;
php bin/console doctrine:database:create --env=test --if-not-exists;

php bin/console doctrine:schema:update --force;
php bin/console doctrine:schema:update --force --env=test;

php bin/console doctrine:fixtures:load --no-interaction;
php bin/console doctrine:fixtures:load --no-interaction --env=t
est;
```

##Testing
Run
`php bin/phpunit;`

## Usage
Use the dedicated Vue.js app ubitransport-app

Or Postman to test the routes

A documentation of the routes can be found at: api/doc

The document is partial, it describes routes, expected params only and return codes with a quick description only. To be continued.
