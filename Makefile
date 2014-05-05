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

dependencies: public/assets/zurb

ifdef YOUR_COMPOSER_VERSION
	composer install
else
	@echo Composer not found. Installing local composer.phar from getcomposer.org.
	@curl -sS https://getcomposer.org/installer | php -- --install-dir=./
	./composer.phar install 
endif

public/assets/zurb:
	mkdir -p public/assets/zurb && rm -rf public/assets/zurb/*
	curl --output public/assets/zurb/foundation.zip http://foundation.zurb.com/cdn/releases/foundation-5.2.2.zip
	cd public/assets/zurb && unzip foundation.zip && rm foundation.zip

clean:
	rm -rf public/assets/zurb vendor