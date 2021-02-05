start:
	@docker-compose up --build;

install:
	@docker-compose exec -T php-fpm composer install;
	@docker-compose exec -T php-fpm php bin/console doctrine:database:create --if-not-exists;
	@docker-compose exec -T php-fpm php bin/console doctrine:schema:update --force;
	@docker-compose exec -T php-fpm php bin/console doctrine:fixtures:load --no-interaction;

test:
	@docker-compose exec -T php-fpm php bin/console doctrine:fixtures:load --no-interaction;
	@docker-compose exec -T php-fpm php ./vendor/bin/phpunit;
