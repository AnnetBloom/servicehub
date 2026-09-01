Company — клиент нашей компании.

Site — конкретный физический объект этого клиента, где мы оказываем услуги.

Company
   │
   └── Site
          │
          └── Device

1. companies

Клиенты ServiceHub.

id
name
website
created_at
updated_at

Company
 ├── hasMany Contacts
 ├── hasMany Sites

2. contacts

Контактные лица компании.

id
company_id
name
position (IT Director, Procurement Manager,Account Manager)
phone
email
created_at
updated_at

Связь:

Company 1 → N Contacts

3. sites

Конкретные места, где находится инфраструктура клиента. (объект/площадка клиента)

id
company_id
name
address
geo_data — если понадобится
created_at
updated_at

Например:

ABC
├── Head Office
├── Branch #1
└── Warehouse


site_contact:

site_id
contact_id

Company 1 ─── N Contact
Site N ────── N Contact
Company 1 ─── N Site

4. contracts

Договоры на обслуживание.

id
company_id  FK → companies.id
date_start — дата начала действия
date_end — дата окончания, nullable
price — стоимость договора
currency валюта
billing_period 
status
file — nullable  путь к файлу договора
notes — nullable
created_at
updated_at

billing_period:

monthly
quarterly
yearly
one_time

status:

draft
active
expired
terminated

Company 1 ─── N Contract

5. device_types

Простой справочник типов оборудования.

id
name
description
created_at
updated_at

Например:

Router
Switch
Firewall
Access Point
Server
Storage
IP Phone
Camera
UPS

6. device_models

Конкретные модели оборудования.

id
device_type_id
vendor
name
description
created_at
updated_at

Например:

device_type: Switch
vendor: Cisco
name: Catalyst 9300-48P

Отдельную таблицу vendors пока не делаем. Если производителей станет много и появится реальная необходимость — вынесем позже.

7. devices

Конкретное физическое оборудование клиента.

devices

id
company_id
site_id
device_model_id

name
serial_number
asset_tag nullable

purchase_date nullable
installed_at nullable

status
notes nullable

created_at
updated_at

name — внутреннее понятное имя устройства, например Core Switch;
device_model_id — какая это модель;
serial_number — заводской серийный номер;
asset_tag — внутренний инвентарный номер;
purchase_date — когда купили;
installed_at — когда установили;
status — например active, inactive, maintenance, removed;
notes — дополнительные сведения.

Например:

Cisco Catalyst 9300-48P
Serial: FOC123456
Company: ABC
Site: Head Office
Status: active

Пока не делаем отдельные таблицы/колонки для CPU, RAM, firmware, количества портов и т.д. Не усложняем.



8. contract_devices

Связь между договором и оборудованием.
Какие устройства покрывает какой договор.

id
contract_id
device_id
created_at
updated_at

Потому что один договор может покрывать много устройств, а одно устройство потенциально может переходить с одного договора на другой.

Contract N ↔ N Device

companies
    │
    ├── contacts
    │
    ├── sites
    │      │
    │      └── devices
    │              │
    │              └── device_models
    │                      │
    │                      └── device_types
    │
    └── contracts
             │
             └── contract_devices
                       │
                       └── devices




sites ─── site_contact ─── contacts

конец зафиксированного.
*******************

device_history - пока не делаем
Тем более твой пример с заменой уже можно частично представить через:
Device lifecycle history may be introduced later if operational requirements show a need for structured tracking of device movements, maintenance, replacement and disposal.

старое Device #15 → status = removed
новое Device #28 → status = active

А полноценную историю добавим, если увидим, что без неё действительно неудобно.

device_history :

id
device_id
ticket_id nullable
action
performed_at
performed_by
notes

Например:

device_id: 15
site_id: 3
action: replaced
performed_at: 2026-08-24
performed_by: 7
notes: "Заменён из-за неисправности"

Device #15
    ↓
action: installed
    ↓
action: maintenance
    ↓
action: repaired
    ↓
action: replaced
    ↓
action: removed

repaired — устройство было сломано и его починили;
maintenance — устройство обслужили, хотя оно могло нормально работать.

ticket_id = NULL — если действие было плановым.

ticket_id = 125 — если действие произошло в рамках обращения.

site_id — на каком объекте клиента установлено устройство.
name — понятное имя устройства внутри системы, например Core Switch, Router, Server-01.
installed_at — дата установки.
manufacturer — производитель, например Cisco, Dell, HP.
model — конкретная модель.
serial_number — серийный номер конкретного экземпляра.

Serial number — уникальный заводской номер, который присвоил производитель.

Asset tag — номер, который присвоила организация для собственного учёта.

purchase_date

Это дата покупки оборудования.

Например:

purchase_date = 2025-04-15

То есть клиент купил этот Cisco 15 апреля 2025 года.

Она может быть полезна, например, чтобы понимать:

сколько лет оборудованию;
примерно когда заканчивается гарантия;
какое оборудование пора заменять.

Но здесь есть вопрос: нужна ли она нам прямо сейчас?

Я думаю, да, можно оставить, но сделать nullable.

***

Company 1 ─────── N Contacts
Site * ──── * Contact

Company
  ├── hasMany Contacts
  └── hasMany Sites

Contact
  ├── belongsTo Company
  └── belongsToMany Sites

Site
  ├── belongsTo Company
  └── belongsToMany Contacts

у компании может быть несколько контактов.

у одной компании потенциально может быть несколько объектов/площадок.
Компания "ABC Bank"
    ├── Головной офис
    ├── Филиал на Навои
    └── Дата-центр


Допустим, клиент:

Kapital Bank

У него есть:

головной офис;
несколько филиалов;
дата-центр.

Это три Site.

А контакты:

IT Director;
Procurement Manager;
Account Manager.

Они могут взаимодействовать с нашей компанией по разным вопросам.

Поэтому мы пришли к:

Company
   │
   ├── Contact
   ├── Contact
   │
   ├── Site
   │      ├── Contact
   │      └── Contact
   │
   └── Site
          └── Contact


оборудование установлено на конкретном объекте клиента.

Kapital Bank
│
├── Головной офис
│   ├── Cisco Catalyst 9300
│   ├── Cisco Access Point
│   └── Dell PowerEdge Server
│
└── Филиал №1
    ├── Cisco Catalyst 9200
    └── MikroTik Router


    Представь:

Cisco Catalyst 9300
модель: C9300-48P
serial number: FOC1234567



Ситуация №2 — оборудование

Инженер приехал в филиал клиента. В ServiceHub он видит:

Site: Филиал №1

Devices:
- Router-01
- Switch-01
- AP-01
- AP-02

Он обнаружил, что Switch-01 неисправен и его нужно заменить.

Инженер снимает старый коммутатор и устанавливает новый:

старый:
Cisco Catalyst 2960
serial: ABC123

заменили на:

новый:
Cisco Catalyst 9200
serial: XYZ789

Через год клиент спрашивает:

«А какое оборудование у нас раньше стояло в филиале №1? Когда его заменили и по какой причине?»

Тогда старый коммутатор остаётся:

devices

id: 15
site_id: 3
name: Switch-01
serial: ABC123
status: removed

А новый:

devices

id: 28
site_id: 3
name: Switch-01
serial: XYZ789
status: active

Company
│
├── Contact
│
├── Site
│    └── Device
│
├── Contract
│     └── ContractDevice
│
└── Ticket
       ├── Contract
       └── Device

***

DeviceType
    │
    ▼
DeviceModel
    │
    ▼
Device
    ├── Company
    └── Site


    Мы не привязываем Device напрямую к Contract через contract_id.

Потому что тогда возникнет проблема:

А если устройство сегодня обслуживается по договору №1, а через полгода его перевели на договор №2?

Историю потом будет неудобно хранить.

contract_devices даёт нам нормальную основу для этого.

Contract у нас фактически лучше понимать именно как Service Contract, а не как любой договор компании. Потому что в нашем ServiceHub основная задача — управление техническим обслуживанием, заявками, оборудованием и инженерами. У Winncom сервисные договоры определяют, какое оборудование и в каком режиме поддерживается, включая 8×5/24×7, диагностику, замену, выезд и т. д.


Теперь представим реальную ситуацию.

Наша компания заключила с ABC Bank договор на обслуживание.

В договоре написано, что мы обслуживаем:

сеть;
Wi-Fi;
серверы;

и оказываем поддержку, например, с 9:00 до 18:00 в рабочие дни.

Также у договора есть:

дата начала;
дата окончания;
стоимость;
статус.

Через год договор заканчивается, и клиент его продлевает.

Я бы не называл поле text — notes понятнее: там могут быть дополнительные условия, комментарии менеджера и т.п.

Но есть один важный вопрос с price

Если договор может быть, например:

обслуживание сети — $500 в месяц
договор на 1 год

то одного price недостаточно, потому что непонятно, это $500/month, $6000/year или общая сумма договора.

Поэтому в более реалистичном варианте:

price
currency
billing_period — например monthly, quarterly, yearly, one_time

status:
draft — подготовлен, но ещё не подписан
active — действует
expired — закончился по сроку
terminated — расторгнут досрочно

Contract → Company — это belongsTo, а Company → Contract — hasMany.




Company 1 ─────── N Contract

Company
    hasMany(Contract::class)

Contract
    belongsTo(Company::class)


    ******

    devices позволяет узнать состояние и основные даты устройства, но не даёт полноценную последовательную историю событий.

Например, сейчас:

devices

id | site_id | installed_at | status
15 | 1       | 2025-03-10   | removed
27 | 1       | 2026-08-27   | active

Мы можем понять:

устройство №15 когда-то установили;
сейчас оно снято;
устройство №27 установили позже;
оба были на site_id = 1.

Но мы не знаем из самой devices, например:

27 августа инженер снял №15 потому, что он сгорел, и вместо него установил №27.

Это можно найти в ticket и comments, если инженер это написал. Но это уже текст, а не структурированные данные.

И вот здесь есть два разных понятия, которые важно не смешать:

devices — текущее состояние

Она отвечает примерно на:

Что это за устройство и что с ним сейчас?

Device #27
Site #1
installed_at: 2026-08-27
status: active
serial_number: XYZ789
История — события

Она отвечает:

Что происходило с устройством во времени?

2025-03-10 → установлено
2026-05-12 → отправлено на обслуживание
2026-05-20 → возвращено
2026-08-27 → снято


Сделаем простую специализированную историю жизненного цикла устройства.

device_history
--------------
id
device_id
event_type
old_site_id nullable
new_site_id nullable
changed_by
notes nullable
created_at

event_type:

installed
moved
sent_to_repair
returned_from_repair
removed
written_off
Как это будет выглядеть

Перенесли устройство:

device_id = 15
event_type = moved
old_site_id = 1
new_site_id = 2
changed_by = 7

Отправили в ремонт:

device_id = 15
event_type = sent_to_repair
notes = "Неисправность блока питания"
changed_by = 7

Вернули:

device_id = 15
event_type = returned_from_repair
notes = "Блок питания заменён"
changed_by = 7

Списали:

device_id = 15
event_type = written_off
notes = "Не подлежит ремонту"
changed_by = 7
А замену?

Не будем делать отдельное сложное событие replaced.

Замена — это две операции:

старое устройство #15
        ↓
     removed

новое устройство #27
        ↓
     installed

А подробности самой замены могут находиться в Ticket/Comment.

Так модель остаётся простой.