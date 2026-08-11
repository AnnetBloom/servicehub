

docker compose -f docker/docker-compose.yml ps

docker compose -f docker/docker-compose.yml exec postgres psql -U servicehub -d servicehub
 - подключиться к базе

внутри psql:

\l - список баз данных

postgres    — стандартная база
servicehub  — наша база
template0   — системный шаблон
template1   — системный шаблон

\q - выход из psql
