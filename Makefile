help:
	@echo 'make [clean|install|test|refresh|import]'

install: 
	bower install
	composer install

test:
	vendor/bin/phpunit

refresh:
	@php artisan migrate:refresh

import:
	@php artisan import:instapaper && php artisan import:pandora

clean:
	rm -rf public/assets/vendor vendor