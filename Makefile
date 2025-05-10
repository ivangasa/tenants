#!/bin/bash

.PHONY: build clean
.DEFAULT_GOAL := help

UID = $(shell id -u)
GID = $(shell id -g)
GUID = "$(UID):$(GID)"

CURRENT_DIR := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
WORKING_DIR = /app

DOCKER_CONTAINER_NAME = tenants
DOCKER_EXEC = docker exec -it -w $(WORKING_DIR) $(DOCKER_CONTAINER_NAME) bash

##
## ——  🐳   Project Tooling & Docker  ———————————————————————————————————————————————————————
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
	@rm -rf var/log
	@rm -rf etc/postgresql/data
	@rm -f *.lock
	@docker compose down --rmi all -v --remove-orphans

start: ## Start Docker containers
	@clear
	@docker compose up


##
help:
	@clear
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
