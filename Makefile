YOUR_COMPOSER_VERSION := $(shell composer --version 2>/dev/null)

help:
	@echo 'make [install|test|deploy|refresh|import]'

install: dependencies

deploy:
	git checkout master && git merge development && git checkout development && git push --all

test:
	vendor/bin/phpunit

refresh:
	@./artisan migrate:refresh

import:
	@./artisan import:instapaper && ./artisan import:pandora

dependencies:

ifdef YOUR_COMPOSER_VERSION
	composer install
else
	@echo Composer not found. Installing local composer.phar from getcomposer.org.
	@curl -sS https://getcomposer.org/installer | php -- --install-dir=./
	./composer.phar install 
endif