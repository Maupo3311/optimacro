DOCKER_COMPOSE = docker compose -f docker-compose.yml
PHP_CONTAINER = php-cli
.PHONY: *
.DEFAULT_GOAL := help

help: ## Показывает справку по Makefile
	@printf "\033[33m%s:\033[0m\n" 'Доступные команды'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-18s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

init: ## Сборка и запуск проекта
	-${MAKE} stop start install

tests: ## Запуск тестов
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php vendor/bin/codecept run --steps

install: ## Установка зависимостей
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) composer i -a -o

shell-php: ## Вход в контейенер приложения
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) bash

clean: ## Остановка и очистка контейнеров
	$(DOCKER_COMPOSE) down --rmi local -v
start: ## Запуск контейнеров
	$(DOCKER_COMPOSE) up -d
restart: stop start
stop: ## Остановка контейнеров
	$(DOCKER_COMPOSE) down

ifeq (run, $(firstword $(MAKECMDGOALS)))
  COMMAND_ARGS_INLINE := $(wordlist 2, $(words $(MAKECMDGOALS)), $(MAKECMDGOALS))
  COMMAND_ARGS := $(subst , , $(COMMAND_ARGS_INLINE))
  $(eval $(COMMAND_ARGS):;@: )
endif
run:
	$(DOCKER_COMPOSE) exec $(PHP_CONTAINER) php command.php -i $(wordlist 1, 1, $(COMMAND_ARGS)) -o $(wordlist 2, 2, $(COMMAND_ARGS))
