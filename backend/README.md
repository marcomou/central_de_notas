# Central de custódia
## Installation
### Git
```sh
git clone git@bitbucket.org:redeciclo/nhecosystem-api.git
```
ou
```sh
git clone https://{user-bitbucket}@bitbucket.org/redeciclo/nhecosystem-api.git
```
## Development
#### Normal
```sh
composer install
cp .env.example .env
php artisan key:generate
```
#### Docker
```sh
 (sudo) docker-compose up --remove-orphans --force-recreate --build
```
Configure your enviroment vars (.env)
* Database
* Filesystem

Open your favorite Terminal and run these commands. 

Running with docker:
```sh
 (sudo) docker-compose exec app bash
```

First Tab:

```sh
php artisan migrate
```

Second Tab:

```sh
php artisan db:seed
```
or
```sh
php artisan migrate --seed
```

Configure passport clients:

```sh
php artisan passport:install (--force)
```

Access http://127.0.0.1:8000

API Explorer http://127.0.0.1:8000/api-explorer
