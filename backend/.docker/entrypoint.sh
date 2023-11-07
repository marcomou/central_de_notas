#!/bin/bash

#On error no such file entrypoint.sh, execute in terminal - dos2unix .docker\entrypoint.sh
chown -R www-data:www-data .

composer install

ENV_FILE=./.env

if [ -f "$ENV_FILE" ]; then
    echo "$ENV_FILE" exists;
else
    cp .env.example .env
fi

php artisan key:generate

php artisan migrate

php-fpm