Здесь есть одна особенно важная строка:

fastcgi_pass php:9000;

php — это имя сервиса из твоего docker-compose.yml:

services:
  php:

Docker сам позволяет контейнерам обращаться друг к другу по имени сервиса.

***

А зачем тогда вообще 9000?

Потому что php:8.5-fpm — это PHP с PHP-FPM, и FPM внутри контейнера слушает порт 9000.

Причём этот порт не нужно публиковать на компьютер:

php:
  # ports: ← НЕ нужен

Nginx и PHP находятся в одной Docker-сети и могут общаться напрямую.

Это как раз типичная схема:

Nginx отвечает внешнему миру, PHP-FPM выполняет PHP-код.

И PostgreSQL тоже отдельный контейнер:

Browser
   ↓ HTTP
Nginx
   ↓ FastCGI
PHP-FPM / Laravel
   ↓ PostgreSQL protocol
PostgreSQL