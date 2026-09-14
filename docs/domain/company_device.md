

Таблицы

1. companies

Клиенты ServiceHub.

id PK
name
website nullable
deleted_at nullable
created_at
updated_at

Company
 ├── hasMany Contacts
 ├── hasMany Sites
 Company 1 ─── N Contract

 Если deleted_at IS NULL → запись действующая

 2. contacts

Контактные лица компании. рабочий контакт клиента.

id PK
company_id FK -> companies.id ON DELETE - RESTRICT INDEX
name
position  string (IT Director, Procurement Manager,Account Manager)
phone
email nullable 
deleted_at nullable
created_at
updated_at

Связь:

Company 1 → N Contacts

3. sites

Конкретные места, где находится инфраструктура клиента. (объект/площадка клиента)

id PK
company_id FK FK -> companies.id ON DELETE - RESTRICT INDEX
name
address
geo_data nullable
deleted_at nullable
created_at
updated_at

Связь:

Company 1 → N Site

4. site_contact:

site_id FK FK -> sites.id ON DELETE - CASCADE INDEX
contact_id FK -> contacts.id ON DELETE - CASCADE INDEX

UNIQUE(site_id, contact_id)

Company 1 ─── N Contact
Site N ────── N Contact
Company 1 ─── N Site

5. contracts

Договоры на обслуживание. Удалить нельзя

id PK
company_id  FK → companies.id ON DELETE - RESTRICT INDEX
date_start — дата начала действия
date_end — дата окончания, nullable
price  numeric(12, 2) — стоимость договора
currency varchar(3) валюта
billing_period  enum
status enum
file — nullable  путь к файлу договора
notes — nullable
created_at
updated_at

USD
UZS
EUR

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

Договор принадлежит компании, удалить компанию при наличии договоров нельзя.

draft — черновик
Договор создали в системе, но он ещё не вступил в силу. Например, договор готовится к подписанию.
active — действующий
Договор подписан и сейчас действует. Услуги клиенту оказываются в рамках этого договора.

expired — истёк
Срок договора закончился естественным образом. Например:

date_start = 2026-01-01
date_end   = 2026-12-31

После окончания срока договор становится expired.

terminated — расторгнут досрочно
Договор прекратили до запланированной даты окончания.

date_end может быть NULL, если договор бессрочный.

Company 1 ─── N Contract

6. device_types удалить нельзя

Простой справочник типов оборудования.

id PK
name UNIQUE
description
created_at
updated_at

7. device_models удалить нельзя

Конкретные модели оборудования.

id PK
device_type_id FK -> device_types.id ON DELETE - RESTRICT INDEX
vendor
name 
description
created_at
updated_at

UNIQUE(vendor, name)

8. devices 

Конкретное физическое оборудование клиента. устройство физически не удаляем

devices

id PK
site_id FK -> sites.id ON DELETE - RESTRICT INDEX
device_model_id FK -> device_models.id ON DELETE - RESTRICT INDEX

name
serial_number UNIQUE INDEX
asset_tag UNIQUE nullable

purchase_date nullable
installed_at nullable

status enum
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

Device → Site → Company

и компанию можно получить через site.
Но здесь есть нюанс: устройство может существовать на складе и ещё не иметь site. Тогда site_id должен быть nullable либо нам нужна отдельная модель locations.
каждый Device в нашей первой версии обязательно установлен на конкретном Site.

Это сознательное ограничение MVP. Позже, если понадобится склад, мы расширим модель.

9. contract_devices удалить нельзя

Связь между договором и оборудованием.
Какие устройства покрывает какой договор.

id PK
contract_id FK -> contracts.id ON DELETE - RESTRICT INDEX
device_id FK -> devices.id ON DELETE - RESTRICT INDEX
created_at
updated_at

UNIQUE(contract_id, device_id)
Contract N ↔ N Device

Потому что один договор может покрывать много устройств, а одно устройство потенциально может переходить с одного договора на другой.
Если устройство было:

Contract A
    ↓
Contract B

а мы просто удалили связь A и создали B, мы потеряли информацию о том, что раньше оно покрывалось A.

Для MVP Просто:

contract_devices

показывает текущую связь.

А если позже понадобится история покрытия — добавим started_at / ended_at или отдельную историю.



10. device_history удалить нельзя
--------------
id PK
device_id FK -> devices.id ON DELETE RESTRICT INDEX
event_type varchar
old_site_id FK → sites.id nullable ON DELETE RESTRICT INDEX
new_site_id FK → sites.id nullable ON DELETE RESTRICT INDEX
changed_by FK → users.id ON DELETE RESTRICT INDEX
event_at
notes nullable
created_at

event_type:

installed
moved
sent_to_repair
returned_from_repair
removed
written_off

и CHECK:

CHECK (
    event_type IN (
        'installed',
        'moved',
        'sent_to_repair',
        'returned_from_repair',
        'removed',
        'written_off'
    )
)

И потом сравнить это с PostgreSQL ENUM.ENUM — не просто «список допустимых значений». У него есть последствия для изменения схемы.

***
для большинства бизнес-сущностей :
companies → contacts
companies → sites
sites → devices

ON DELETE - RESTRICT / NO ACTION

Не разрешить удалить Company, пока у неё есть Contacts.

ля чисто зависимых технических таблиц — CASCADE.
Удаляем 1 → автоматически удаляются 2.

tickets → comments ON DELETE CASCADE
tickets → ticket_status_history ON DELETE CASCADE

site_contact CASCADE
site deleted
   ↓
его строки site_contact удаляются

contact deleted
   ↓
его строки site_contact удаляются

В PostgreSQL индекс на FK не создаётся автоматически просто от объявления FOREIGN KEY, поэтому мы должны продумать их отдельно.


