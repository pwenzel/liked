help:
	@echo 'make [clean|install|test|refresh|import]'

install: 
	bower install
	composer install

test:
	vendor/bin/phpunit

refresh:
	@./artisan migrate:refresh

import:
	@./artisan import:instapaper && ./artisan import:pandora

clean:
	rm -rf public/assets/vendor vendor