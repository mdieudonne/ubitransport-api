install:
	@docker-compose up --build;
	@docker-compose exec -T php-fpm composer install;
	@docker-compose exec -T php-fpm php bin/console doctrine:database:create --if-not-exists;
	@docker-compose exec -T php-fpm php bin/console doctrine:database:create --env=test --if-not-exists;
	@docker-compose exec -T php-fpm php bin/console doctrine:schema:update --force;
	@docker-compose exec -T php-fpm php bin/console doctrine:schema:update --force --env=test;
	@docker-compose exec -T php-fpm php bin/console doctrine:fixtures:load --no-interaction;
	@docker-compose exec -T php-fpm php bin/console doctrine:fixtures:load --no-interaction --env=test;

install-test:
	@docker-compose -f docker-compose.test.yml up --build;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm composer install;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm php bin/console doctrine:database:create --if-not-exists;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm php bin/console doctrine:database:create --env=test --if-not-exists;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm php bin/console doctrine:schema:update --force;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm php bin/console doctrine:schema:update --force --env=test;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm php bin/console doctrine:fixtures:load --no-interaction;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm php bin/console doctrine:fixtures:load --no-interaction --env=test;
	@docker-compose -f docker-compose.test.yml exec -T php-fpm composer req --dev phpunit/phpunit;

test:
	@docker-compose -f docker-compose-test.yml exec -T php-fpm composer req --dev phpunit/phpunit;
	@docker-compose -f docker-compose-test.yml exec -T php-fpm php ./vendor/bin/phpunit;
