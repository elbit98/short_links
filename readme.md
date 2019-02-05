## Установка

- Переименовать .env.example в .env, указать данные БД, APP_URL - указать адрес проекта.

Запустить:
- composer update.
- php artisan key:generate.
- php artisan migrate.
- chmod 777 -R storage/
