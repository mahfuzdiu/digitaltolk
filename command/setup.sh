#!/bin/sh

if [ ! -f .env ]; then
    echo "No .env found, creating from .env.example..."
    cp .env.example .env
fi

sed -i 's/DB_HOST=.*/DB_HOST=db/' .env
sed -i 's/DB_PORT=.*/DB_PORT=5432/' .env
sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE=translation_db/' .env
sed -i 's/DB_USERNAME=.*/DB_USERNAME=db_user/' .env
sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=db_pass/' .env

# 1. Load the .env file into the shell environment
if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi

# 2. Now $APP_KEY refers to the value inside the .env file
if [ -z "$APP_KEY" ]; then
    echo "No APP_KEY found, generating one..."
    php artisan key:generate
fi

# 1. Wait for Postgres to be ready (important!)
echo "Waiting for database at $DB_HOST:5432..."
until pg_isready -h "$DB_HOST" -p 5432 -U "${DB_USERNAME:-postgres}"; do
  sleep 2
done

# 2. Run migrations
echo "Running migrations..."
php artisan migrate:fresh

echo "Running factories......"
php artisan db:seed

echo "Clearing cache"
php artisan optimize:clear

exec "$@"
