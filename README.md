# dnd_chat

Запуск проекта локально:

```bash
docker compose up -d --build --remove-orphans
```

## TODO
- Хранить секреты в vault
- Завести конфиги для миникуба
- Подумать над разделением групповых соединениях в вебсокете. вынести действия read и write в отдельный каналы
- Подумать над балансировщиком
- Допилить прокси для локальной разработки, сделать ее совместимым с вебсокетами

## БД
Performance Schema:

MySQL Performance Schema — это более мощный инструмент для анализа производительности на продакшене. Он позволяет отслеживать использование ресурсов, блокировки, работу индексов и многое другое.
Пример запроса:
sql
Копировать код
SELECT * FROM performance_schema.events_statements_history WHERE SQL_TEXT LIKE '%users%';
General или Slow Query Log:

Настройте логирование медленных запросов (slow_query_log) для выявления проблемных запросов:
sql
Копировать код
SET GLOBAL slow_query_log = 1;
SET GLOBAL long_query_time = 0.5; -- Логировать запросы, дольше 0.5 сек
