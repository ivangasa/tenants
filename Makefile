#!/bin/bash

.PHONY: build clean
.DEFAULT_GOAL := help

UID = $(shell id -u)
GID = $(shell id -g)
GUID = "$(UID):$(GID)"

CURRENT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
WORKING_DIR = /app

API_CONTAINER_NAME = tenants_api
DOCKER_EXEC = docker exec -it -w $(WORKING_DIR) $(API_CONTAINER_NAME) bash
API_DOCKER_EXEC = docker exec -it -w /app/apps/api $(API_CONTAINER_NAME) bash

##
## ——  🐳   General Project Tooling  ———————————————————————————————————————————————————————
build: ## Install project libraries
build: clean
build:
	@clear
	@docker compose build

clean: ## Completely cleans the project: docker, logs & cache
clean:
	@clear
	@rm -rf vendor
	@rm -rf var/cache
	@rm -rf var/reports
	@rm -rf var/logs
	@rm -rf etc/postgresql/data
	@rm -f *.lock
	@docker compose down --rmi all -v --remove-orphans

start: ## Start Docker containers
	@clear
	@docker compose up

stop: ## Stop Docker containers
	@clear
	@docker compose stop

restart: ## Restart Docker containers
restart: stop start

ps: ## List Docker containers
	@docker ps

bash: ## Opens an interactive shell inside main container
	@clear
	@$(DOCKER_EXEC)

cc: ## Cleans cache and refresh autoloading
	@$(API_DOCKER_EXEC) -c "bin/console cache:clear"
	@$(DOCKER_EXEC) -c "composer dump-autoload"

composer-update: ## Update composer libraries if any
	@$(DOCKER_EXEC) -c "composer update"

composer-outdated: ## Check which libraries are outdated if any
	@$(DOCKER_EXEC) -c "composer outdated"


##
## ——  💎  Code Quality ————————————————————————————————————————————————————————————————————
stan: ## Run PhpStan to find bugs in your codebase
	@rm -rf var/cache/.phpstan
	@$(DOCKER_EXEC) -c "vendor/bin/phpstan analyse --memory-limit=512M"

ecs: ## Run ecs in read-only mode to check Coding Standards
	@$(DOCKER_EXEC) -c "composer check-ecs"

ecs-fix: ## Apply ECS (Coding Standards) to our entire codebase
	@$(DOCKER_EXEC) -c "composer fix-ecs"

##
## ——  ✅  Testing —————————————————————————————————————————————————————————————————————————
tests: ## Run ALL tests
tests: tests-unit tests-functional tests-integration tests-architecture coverage

tests-unit: ## Run Unit tests
	@$(DOCKER_EXEC) -c "vendor/bin/phpunit --testdox --color=always --no-coverage --testsuite unit"

tests-functional: ## Run Functional tests
	@$(DOCKER_EXEC) -c "vendor/bin/phpunit --testdox --color=always --no-coverage --testsuite functional"

tests-integration: ## Run Integration tests
	@$(DOCKER_EXEC) -c "vendor/bin/phpunit --testdox --color=always --no-coverage --testsuite integration"

tests-architecture: ## Run Architecture tests
	@rm -rf var/cache/.phpstan
	@$(DOCKER_EXEC) -c "vendor/bin/phpstan analyse tests/Architecture --memory-limit=512M"

infection: ## Run Infection to tests mutants
	@$(DOCKER_EXEC) -c "vendor/bin/infection --threads=6 --only-covered"

coverage: ## Create Testing Coverage report
	@clear
	@$(DOCKER_EXEC) -c "XDEBUG_MODE=coverage vendor/bin/phpunit --testdox --color=always --testsuite unit,integration"


##
## ——  🧩   API app  ———————————————————————————————————————————————————————————————————————
bash-api: ## Opens an interactive shell inside api container
bash-api:
	@clear
	@$(API_DOCKER_EXEC)

trust-api: ## Install certificates to make https work
	@docker compose cp tenants_api:/data/caddy/pki/authorities/local/root.crt /tmp/root.crt && sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /tmp/root.crt


##
help:
	@clear
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
