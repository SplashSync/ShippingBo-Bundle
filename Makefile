### ——————————————————————————————————————————————————————————————————
### —— Local Makefile
### ——————————————————————————————————————————————————————————————————

# Register Toolkit as Symfony Container
SF_CONTAINERS += openapi

include vendor/splash/toolkit/make/toolkit.mk
include vendor/badpixxel/php-sdk/make/sdk.mk

upgrade: ## Upgrade Toolkit & Open API Schemas
	@$(DOCKER_COMPOSE) exec toolkit rm -Rf var/cache
	@$(DOCKER_COMPOSE) exec openapi rm -Rf var/cache
	@$(DOCKER_COMPOSE) exec openapi php bin/console doctrine:schema:update --force --complete

test: ## Execute Functional Test
	@$(DOCKER_COMPOSE) exec toolkit php vendor/bin/phpunit tests/Controller/S00MinimalObjectsTest.php --testdox
	@$(DOCKER_COMPOSE) exec toolkit php vendor/bin/phpunit --testdox

