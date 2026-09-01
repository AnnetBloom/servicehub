
в нашем проекте мы предполагаем отдельный backend и frontend.

Упрощённо:

Vue frontend
     │
     │ HTTP/JSON
     ▼
Laravel API
     │
     ▼
PostgreSQL

Например, менеджер в Vue нажимает «Создать заявку». Vue отправляет:

POST /api/tickets
Content-Type: application/json

с JSON-данными. Laravel принимает запрос, валидирует его, сохраняет Ticket в PostgreSQL и возвращает JSON-ответ.

Database design

→ нормализация
→ PK/FK
→ cardinality
→ constraints
→ индексы
→ PostgreSQL

Laravel

→ migrations
→ Eloquent relationships
→ Form Requests / validation
→ services
→ transactions
→ authorization
→ API Resources
→ REST API

Бизнес-логика

→ ticket workflow
→ назначение инженера
→ изменение статусов
→ история устройства

Качество

→ PHPUnit/Pest
→ feature tests
→ database tests
→ обработка ошибок

Инфраструктура

→ Docker
→ PostgreSQL
→ nginx
→ Linux
→ environment/config

И потом

→ Vue
→ API integration
→ authentication
→ permissions
→ нормальный frontend.