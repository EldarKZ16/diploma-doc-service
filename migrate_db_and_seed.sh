#!/bin/sh

php artisan migrate:fresh
composer dump-autoload
php artisan permissions:generate
php artisan db:seed
php artisan passport:client --personal -n
