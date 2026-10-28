

docker compose -f docker/docker-compose.yml ps

docker compose -f docker/docker-compose.yml exec postgres psql -U servicehub -d servicehub
docker compose  exec postgres psql -U servicehub -d servicehub
 - подключиться к базе

внутри psql:

\l - список баз данных

postgres    — стандартная база
servicehub  — наша база
template0   — системный шаблон
template1   — системный шаблон

\q - выход из psql


docker compose -f docker/docker-compose.yml config - посмотреть конфиг compose файла

docker compose -f docker/docker-compose.yml build php - построить только сервис php

PostgreSQL доступен на localhost:5432 с  компьютера.

 docker compose exec php bash - в контейнер php

 если ошибка на права:
 docker compose exec php chown -R www-data:www-data storage bootstrap/cache

 Пользователи:

 pavel@gmail.com
 test12345


 docker compose exec redis redis-cli  - Redis

 SCAN 0 MATCH *queues* COUNT 100 - покажет ключи Redis, связанные с очередями.
 SCAN 0
 LLEN laravel-database-queues:default - посмотрим содержимое laravel-database-queues:default

 LRANGE laravel-database-queues:default 0 -1   - посмотреть сам job 

 Queue::connection('redis')->size('default')  - «Сколько сейчас jobs лежит в очереди default?» 

 ****

 php artisan optimize:clear
 php artisan view:cache

***
У меня отдельный Vue 3 + Vite в каталоге frontend/, а Laravel - API-бэкенд

запустила
npm create vite@latest frontend -- --template vue

Теперь:
 Laravel API — http://localhost:8000
Vue — http://localhost:5173 - в отдельной папке frontend

В дальнейшем:
cd /servicehub/frontend
npm run dev