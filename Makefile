YOUR_COMPOSER_VERSION := $(shell composer --version 2>/dev/null)

help:
	@echo 'make [install|test|deploy]'

install: dependencies

deploy:
	git checkout master && git merge development && git checkout development && git push --all

test:
	vendor/bin/phpunit

dependencies:

ifdef YOUR_COMPOSER_VERSION
	composer install
else
	@echo Composer not found. Installing local composer.phar from getcomposer.org.
	@curl -sS https://getcomposer.org/installer | php -- --install-dir=./
	./composer.phar install 
endif