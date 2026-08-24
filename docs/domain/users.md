сущности



Company
Contact
Site
Device
Ticket
Comment
Contract
User
Role
Permission



Company — КЛИЕНТ , организация, которая является клиентом компании.
Contact - контакты компании по которым можно связаться с человеком
Site — физический объект клиента, где установлено оборудование.
Device — единица оборудования, установленная на объекте.
Ticket — обращение клиента или внутреннее обращение по обслуживанию.
Contract
Comment

ООО "Банк КБ"
│
├── Contact
├── Site
├── Device
└── Ticket

1. User

users                    roles            permissions     role_permission 
-----------              -----------      -----------     ---------------
id                       id                id             id  
name                     name              name           name
email                                      
password                 
role_id ────────────────► id
phone

users
  │
  │ belongsTo
  ▼
roles
  │
  │ belongsToMany
  ▼
permissions

User ──── Role ──── Permission
  1         1          *

Роли:
Manager → работает с клиентами и заявками
Engineer → работает с заявками и оборудованием
Admin → управляет пользователями
Leader - распределяет работу между инженерами, контролирует заявки, может проверять результаты.



role_permission :

# управление пользователями
users.view
users.create
users.update
users.delete

# управление ролями
roles.view - смотреть роли
roles.create создать роль
roles.update ред роль
roles.delete удалить роль

tickets.view - смотреть заявки
tickets.create создавать заявки
tickets.update
tickets.delete удалять заявки

# след возможные значения
clients.view
clients.create

users.create - создать пользователя
equipment.view


1. Manager создаёт заявку

Например, клиент позвонил менеджеру:

«У нас на 3-м этаже не работает Wi-Fi».

Manager создаёт:

Ticket
---------
title: Не работает Wi-Fi
company: ООО Ромашка
site: Офис на Ленина

2. Engineer создаёт внутреннюю заявку

Инженер обнаружил проблему сам:

«На объекте обнаружен неисправный коммутатор».

Он тоже может создать Ticket.

Это как раз соответствует твоему первоначальному определению:

«обращение клиента или внутреннее обращение по обслуживанию».


3. В будущем заявку может создать сам клиент

Например, если мы дадим клиенту доступ к ServiceHub:

ООО Ромашка
     ↓
пользователь клиента
     ↓
Создать заявку

Пока не включать это в первый MVP.