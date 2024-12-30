#!/bin/bash
set -e

# Ждём, пока база данных станет доступна
./wait-for-it.sh --timeout=5 --host=dnd_database --port=3306

# Генерация ключа приложения
if [ ! -f "/var/www/server/storage/oauth-private.key" ]; then
    php artisan key:generate
fi

# Выполнение миграций и сидов
php artisan migrate --seed

# Ждём, пока RabbitMQ станет доступен
./wait-for-it.sh --timeout=5 --host=dnd_queue --port=5672

# Запуск PHP-FPM
exec php-fpm
