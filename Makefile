include .env

.PHONY: codecept-build
codecept-build:
	./vendor/bin/codecept build

.PHONY: codecept-migrate
codecept-migrate:
	./tests/bin/yii migrate

.PHONY: codecept-prepare
codecept-prepare: codecept-build codecept-migrate

.PHONY: codecept-run
codecept-run:
	./vendor/bin/codecept run
