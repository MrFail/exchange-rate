# For local environment

## Install composer
`
composer install
`
## Up container
``
docker-compose up -d
``

## Run migrations
`
docker exec project php artisan migrate --seed
`

