composer install

rm -rf laravel

composer create-project laravel/laravel laravel

rm laravel/tests/Feature/ExampleTest.php
rm laravel/tests/Unit/ExampleTest.php

cp -r tests/* laravel/tests/Feature

php setup.php

cd laravel

composer update

php artisan test

sleep infinity