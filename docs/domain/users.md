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
Administrator → управляет пользователями
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

***

Manager — менеджер клиента / работы

Manager отвечает прежде всего за клиентов и организацию работ.

Представим, что у нас есть клиент — банк.

Manager:

ведёт информацию о компании;
работает с контактами клиента;
смотрит договоры;
принимает/создаёт обращения клиента;
контролирует, чтобы заявки обрабатывались;
назначает инженера на Ticket;
общается с клиентом по поводу работ.

То есть его зона:

Manager
   ↓
Клиенты → Договоры → Заявки → Организация работ

Он не обязательно технический специалист.

***

Leader — технический руководитель

Leader — это руководитель инженерной команды.

Например:

Есть 5 инженеров, которые обслуживают сети, серверы, Wi-Fi и телефонию.

Leader:

видит технические заявки;
распределяет работу между инженерами;
контролирует выполнение;
может менять назначение;
следит за статусами;
контролирует оборудование;
разбирает сложные технические проблемы;
может проверять работу инженеров.

Его зона:

Leader
   ↓
Инженеры → Технические заявки → Оборудование → Контроль

***

Engineer — непосредственно выполняет техническую работу:

Ticket
   ↓
Engineer
   ↓
диагностика → ремонт → замена оборудования → комментарий → изменение статуса

Например, клиент сообщает:

«На третьем этаже не работает Wi-Fi».

Manager может принять заявку и назначить инженера.

Leader может контролировать её выполнение.

Engineer идёт и чинит точку доступа.
***
Administrator вообще находится немного в стороне от бизнес-процесса:

Administrator
   ↓
Users
Roles
Permissions
системные настройки

Он управляет самой системой, а не клиентами и техническими работами.

Manager

ticket.view
ticket.create
ticket.assign
возможно ticket.change_status

Leader

ticket.view
ticket.assign
ticket.change_status
возможно ticket.create

Engineer

ticket.view
ticket.change_status
comment.view
comment.create

Administrator

прежде всего user.*, role.*, permission.*; доступ к рабочим сущностям можно дать отдельно при необходимости.

Administrator может иметь доступ ко всему не потому, что «он главный», а потому что его ответственность — администрирование всей системы.

То есть мы можем принять правило:

Administrator имеет все permissions.

Manager — полноценная работа с компаниями и площадками клиента.
Engineer — только просмотр, потому что инженеру нужно знать, с каким клиентом и на каком объекте он работает, но создавать/редактировать клиентов ему не нужно.
Leader — тоже просмотр.
Administrator — управление системой, поэтому полный доступ к этим сущностям.

Permission	Manager	Engineer	Leader	Administrator
user.view	✓	✓	✓	✓
user.create	—	—	—	✓
user.update	—	—	—	✓
company.view	✓	✓	✓	✓
company.create	✓	—	—	✓
company.update	✓	—	—	✓
site.view	✓	✓	✓	✓
site.create	✓	—	—	✓
site.update	✓	—	—	✓
ticket.view	✓	✓	✓	✓
ticket.create	✓	—	✓	✓
ticket.assign	✓	—	✓	✓
ticket.change_status	✓	✓	✓	✓
device.view	✓	✓	✓	✓
device.create	✓	—	✓	✓
device.change_status	—	✓	✓	✓
contract.view	✓	✓	✓	✓
contract.create	✓	—	—	✓
contract.update	✓	—	—	✓
contact.view	✓	✓	✓	✓
contact.create	✓	—	—	✓
contact.update	✓	—	—	✓
comment.view	✓	✓	✓	✓
comment.create	✓	✓	✓	✓


/*
company.view
company.create
company.update
site.view
site.create
site.update
user.view
user.create
user.update
ticket.view
ticket.create
ticket.assign
ticket.change_status
device.view
device.create
device.change_status
comment.view
comment.create
contract.view
contract.create
contract.update
contact.view
contact.create
contact.update
*/

/*
 Какие действия нужны человеку для выполнения его ответственности?»

 Manager — организует работу с клиентом.
Engineer — выполняет техническую работу.
Leader — руководит инженерами.
Administrator — администрирует систему.

user.view
user.create
user.update

ticket.view
ticket.create
ticket.assign
ticket.change_status

Manager - user.view  ticket.view ticket.create ticket.assign ticket.change_status 
Engineer - user.view ticket.view  ticket.change_status
Leader - user.view  ticket.view ticket.create ticket.assign ticket.change_status 
Administrator - user.view user.create user.update

Manager → user.view 
Engineer → user.view
Leader → user.view
Administrator → user.view user.create user.update

Manager → ticket.view ticket.create ticket.assign ticket.change_status
Engineer → ticket.view ticket.change_status
Leader → ticket.view ticket.create ticket.assign ticket.change_status
Administrator → ticket.view, ticket.create, ticket.assign, ticket.change_status


company.view
company.create
company.update

site.view
site.create
site.update

Manager → company.view company.create  company.update site.view  site.create site.update
Engineer → company.view site.view
Leader → company.view site.view
Administrator → company.view company.create company.update site.view site.create site.update

device.view
device.create
device.change_status

Manager → device.view device.create 
Engineer → device.view device.change_status
Leader → device.create device.view device.change_status
Administrator → device.view device.create device.change_status

contract.view
contract.create
contract.update

Manager → contract.view contract.create contract.update
Engineer → contract.view
Leader → contract.view
Administrator → contract.view contract.create contract.update

contact.view
contact.create
contact.update

Manager →contact.view contact.create contact.update
Engineer → contact.view
Leader → contact.view
Administrator → contact.view contact.create contact.update 

comment.view
comment.create

Manager → comment.view comment.create
Engineer → comment.view comment.create
Leader → comment.view comment.create
Administrator → comment.view comment.create

 */

