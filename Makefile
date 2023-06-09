include .env

.PHONY: start-dev
start-dev:
	docker-compose up -d

.PHONY: start-prod
start-prod:
	docker-compose -f docker-compose.prod.yml up -d

.PHONY: start-test
start-test:
	docker-compose -f docker-compose.test.yml up -d

.PHONY: build-tests
build-tests:
	./vendor/bin/codecept build

.PHONY: migrate-tests
migrate-tests:
	./tests/bin/yii migrate

.PHONY: prepare-tests
prepare-tests: build-tests migrate-tests

.PHONY: run-tests
run-tests:
	./vendor/bin/codecept run

.PHONY: clear-cache
clear-cache:
	php yii cache/flush-all

.PHONY: migrate
migrate:
	php yii migrate
