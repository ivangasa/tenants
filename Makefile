#!/bin/bash

.PHONY: build clean
.DEFAULT_GOAL := help

UID = $(shell id -u)
GID = $(shell id -g)
GUID = "$(UID):$(GID)"

CURRENT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
WORKING_DIR = /app

API_CONTAINER_NAME = tenants_api
API_DOCKER_EXEC = docker exec -it -w $(WORKING_DIR) $(API_CONTAINER_NAME) bash

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
	# @rm -rf vendor
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

##
## ——  🧩   API app  ———————————————————————————————————————————————————————————————————————
bash-api: ## Opens an interactive shell inside api container
bash-api:
	@clear
	@$(API_DOCKER_EXEC)

trust-api: ## Install certificates
	@docker compose cp tenants_api:/data/caddy/pki/authorities/local/root.crt /tmp/root.crt && sudo security add-trusted-cert -d -r trustRoot -k /Library/Keychains/System.keychain /tmp/root.crt


##
## ——  💎  Code Quality ————————————————————————————————————————————————————————————————————

stan: ## Run PhpStan to find bugs in your codebase
	@$(API_DOCKER_EXEC) -c "vendor/bin/phpstan analyse"

ecs: ## Run ecs in read-only mode to check Coding Standards
	@$(API_DOCKER_EXEC) -c "composer check-ecs"

ecs-fix: ## Run ecs in fix mode
	@$(API_DOCKER_EXEC) -c "composer fix-ecs"

infection: ## Run Infection to tests mutants
	@$(API_DOCKER_EXEC) -c "vendor/bin/infection --threads=6 --only-covered"

deptrac: ## Run Deptrac to analyze if we are following Hexagonal Architecture
	@mkdir -p var/reports/deptrac
	@$(API_DOCKER_EXEC) -c "vendor/bin/deptrac analyze --formatter graphviz-dot --output=./var/reports/deptrac/tenants-architecture.dot" || true
	@$(API_DOCKER_EXEC) -c "vendor/bin/deptrac analyze --formatter graphviz-image --output=./var/reports/deptrac/tenants-architecture.svg" || true
	@$(API_DOCKER_EXEC) -c "vendor/bin/deptrac analyze"

##
## ——  ✅  Testing —————————————————————————————————————————————————————————————————————————
tests: ## Run ALL tests
tests: tests-unit tests-functional tests-integration

tests-unit: ## Run Unit tests
	@$(API_DOCKER_EXEC) -c "vendor/bin/phpunit --testdox --color=always --no-coverage --testsuite unit"

tests-functional: ## Run Functional tests
	@$(API_DOCKER_EXEC) -c "vendor/bin/phpunit --testdox --color=always --no-coverage --testsuite functional"

tests-integration: ## Run Integration tests
	@$(API_DOCKER_EXEC) -c "vendor/bin/phpunit --testdox --color=always --no-coverage --testsuite integration"

coverage: ## Create Testing Coverage report
	@clear
	@$(API_DOCKER_EXEC) -c "XDEBUG_MODE=coverage vendor/bin/phpunit --testdox --color=always --testsuite unit,integration"


##
help:
	@clear
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
