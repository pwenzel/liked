Liked
=====

Collect and tag everything you like on the internet.

*This project is a work in progress and not ready for general use.*

Requirements
------------

Liked is based on [Laravel](http://www.laravel.com), and incures the same [server requirements][1]:

* PHP >= 5.3.7
* MySQL, SQLite or other [databases supported by Laravel][2]

[Bower](http://bower.io) is required for installation of front-end dependencies.

Installation
------------

Clone this repository and run the following commands:

	make install
	mysql -u root -proot -e 'create database if not exists liked'
	cp env.local.sample.php .env.local.php
	php artisan migrate

Put your usernames and other configuration options in `.env.local.php`.

~~Load sample data (optional for demonstration purposes):~~

~~`php artisan db:seed`~~


Usage
-----

Import bookmarks and other saved content from providers like so:

	php artisan import:pandora
	php artisan import:instapaper

View your web site locally at [http://localhost:8000](http://localhost:8000):

	php artisan serve


Supported Services
------------------

* [Last FM](http://www.last.fm)
* [Pandora](http://www.pandora.com)
* [Instapaper](http://www.instapaper.com)
* ~~[Embedly Extract API](http://embed.ly/extract)~~
* [Readability API](https://www.readability.com/developers/api)


Vendor Dependencies
-------------------

* [Laravel](http://www.laravel.com)
* [Requests for PHP](http://requests.ryanmccue.info)
* [Zurb Foundation](http://foundation.zurb.com)


Updating Dependencies
---------------------

Update PHP libraries with Composer

	composer update

Update front-end libraries with Bower

	bower update
	
Purge Dependencies and Reinstall

	make clean
	make install


[1]: http://laravel.com/docs/installation#server-requirements "Laravel Server Requirements"
[2]: http://laravel.com/docs/database "Laravel Databases"