## Установка

- Переименовать .env.example в .env, указать данные БД, APP_URL - указать адрес проекта.

Выполнить команды:
- composer update.
- php artisan key:generate.
- php artisan migrate.
- chmod 775 -R storage/
