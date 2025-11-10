copy .env.example to .env

edit .env (Database Credentails)

DB_CONNECTION=
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=

php artisan migrate

php artisan key:generate
