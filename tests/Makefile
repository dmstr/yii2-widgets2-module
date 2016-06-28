.PHONY: build test

DOCKER_COMPOSE  ?= docker-compose

WEB_SERVICE ?= nginx
PHP_SERVICE ?= phpfpm
DB_SERVICE  ?= db

UNAME_S := $(shell uname -s)
ifeq ($(UNAME_S), Darwin)
	OPEN_CMD        ?= open
	DOCKER_HOST_IP  ?= $(shell echo $(DOCKER_HOST) | sed 's/tcp:\/\///' | sed 's/:[0-9.]*//')
else
	OPEN_CMD        ?= xdg-open
	DOCKER_HOST_IP  ?= 127.0.0.1
endif

all: build up setup open

build:
	$(shell echo $(shell git describe --long --tags --dirty --always) > src/version)
	@echo $(shell cat src/version)
	$(DOCKER_COMPOSE) build --pull

up:
	$(DOCKER_COMPOSE) up -d

clean:
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) rm -fv --all
	$(DOCKER_COMPOSE) down --rmi local --remove-orphans

open:	 ##@docker open application web service in browser
	$(OPEN_CMD) http://$(DOCKER_HOST_IP):$(shell $(DOCKER_COMPOSE) port $(WEB_SERVICE) 80 | sed 's/[0-9.]*://')

open-db:	 ##@docker open application web service in browser
	$(OPEN_CMD) mysql://root:secret@$(DOCKER_HOST_IP):$(shell $(DOCKER_COMPOSE) port $(DB_SERVICE) 3306 | sed 's/[0-9.]*://')

bash:	 ##@docker open application development bash
	$(DOCKER_COMPOSE) run --rm $(PHP_SERVICE) bash

setup:	 ##@docker open application development bash
	-$(DOCKER_COMPOSE) run --rm $(PHP_SERVICE) yii db/create
	-$(DOCKER_COMPOSE) run --rm $(PHP_SERVICE) yii migrate

lint:
	mkdir -p _artifacts/lint && chmod -R 777 _artifacts/lint
	docker run --rm -v "${PWD}:/project" jolicode/phaudit php-cs-fixer fix --format=txt -v --dry-run src || export ERROR=1; \
	docker run --rm -v "${PWD}:/project" jolicode/phaudit phpmetrics --report-html=_artifacts/lint/metrics.html src/ || ERROR=1; \
	docker run --rm -v "${PWD}:/project" jolicode/phaudit phpmd src html cleancode,codesize,controversial,design,unusedcode,tests/phpmd/naming.xml > _artifacts/lint/mess.html || ERROR=1; \
	exit ${ERROR}