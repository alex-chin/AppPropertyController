
<img alt="музторг" src="https://dev-c2c.muztorg.ru/images/logo-bis.svg" width="200">

# Управление параметрами объявлений

## Контекст общей сситемы

Музыкальные инструменты являются дорогостоящим, узкоспециализированными товарами. Предназначение таких товаров определяет отдельный сектор рынка. Существует возможность создать условия и перенести большую часть объявлений по продаже музыкальных инструментов и других сопутствующих товаров и технологий на отдельную отраслевую площадку, используя опыт “Музторга” и осуществляя тесную интеграцию с ресурсами компании.
Предназначение площадки - предоставление возможности взаимодействия продавца и покупателя для быстрой продажи и выгодной покупки.

Существующие реализации:

| Площадка      | Основной сайт          | Раздел                                                             | Кол       |
|---------------|------------------------|--------------------------------------------------------------------|-----------|
| Avito         | https://www.avito.ru/  | https://www.avito.ru/moskva/muzykalnye_instrument                  | 84000     |
| Купипродай    | https://kupiprodai.ru/ | https://kupiprodai.ru/otdyh/muzika                                 | агрегатор |
| Юла           | https://youla.ru/      | https://youla.ru/moskva/hobbi-razvlecheniya/muzykalnye-instrumenti | 1000      |
| Из рук в руки | https://irr.ru/        | https://irr.ru/hobbies/arts-music/instruments/                     | 135       |


## Функциональный состав

[Варианты использования системы]([https://docs.google.com/document/d/1QqTMdcU8uxgeLDevJ8bnG1IjimOaZiomV9RzvJVMRdw/edit#bookmark=id.9azjq071rope)

| Пакет | Вариант использования                | Раздел Детального описания компонентов                                                |
|-------|--------------------------------------|---------------------------------------------------------------------------------------|
| PK03  | UC01. Регистрация на площадке        | Пользователь, профиль пользователя, процесс логина и регистрации                      |
| PK03  | UC02. Идентификация                  | Процесс регистрации и идентификации                                                   |
| PK03  | UC03. Идентификация muztorg.ru       | Процесс регистрации и идентификации                                                   |
| PK01  | UC04. Описание объявления            | Состав элементов карточки объявления                                                  |
| PK01  | UC05. Размещение объявления          | Изменение статуса согласно Система статусов объявления                                |
| PK04  | UC06. Модерация                      | Модерация                                                                             |
| PK05  | UC07. Поиск объявления               | Лента выдачи                                                                          |
| PK02  | UC08. Обсуждение и совершение сделки | Движок чатов                                                                          |
| PK04  | UC09. Ведение классификатора         | Категорийная метамодель категорий и параметров                                        |
| PK05  | UC10. Система ранжирования           | Веса, Формула ранжирования                                                            |
| PK01  | UC11. Система расчета расстояний     |                                                                                       |
| PK04  | UC12. Ведение НСИ                    |                                                                                       |
| PK04  | UC13. Аналитика                      | Дашборд продуктовых метрик                                                            |
| PK04  | UC14. Разграничение доступа          |                                                                                       |
| PK01  | UC15. Работа с фото                  |                                                                                       |
| PK06  | UC16. Ведение профиля                | Атрибуты пользователя                                                                 |
| PK06  | UC17. Избранное                      |                                                                                       |
| PK06  | UC18. Оповещения                     |                                                                                       |
| PK06  | UC19. Личный кабинет, мои объявления | Страница личного кабинета - мои объявления, избранное, списки чатов, оповещения и пр. |

## Документация
- [Система доска объявлений BB(C2C). Требования к программному обеспечению.](https://docs.google.com/document/d/1QqTMdcU8uxgeLDevJ8bnG1IjimOaZiomV9RzvJVMRdw/edit)
- [Дизайн-макет](https://www.figma.com/file/FgYrivy3U8nkVItWTX63d0/%D0%9C%D1%83%D0%B7%D0%A2%D0%BE%D1%80%D0%B3?node-id=37%3A737)
- [Дизайн C2C. Дополнения ](https://disk.yandex.ru/edit/d/8gYd4H_LvSgSXcEoxv2vGSPegnqahzm72s0qoIz-cKg6WnVxaFZrWU5DUQ?source=docs&sk=yd5e2b8198b05490ff360173c71c1ce5e)
- [Классификатор](https://docs.google.com/spreadsheets/d/1YAZP6m6lH3xwzs8X55lKLyANUeuF9flneVLsiRXfcbA/edit#gid=0)
- [ПАРАМЕТРЫ ГРАФИКИ ДЛЯ ДИЗАЙНА C2C](https://docs.google.com/document/d/1x0J3xLLCpBd6ZB4jXKUkNn6Xsj0Vj2OdxJs3dKbih0k/edit#heading=h.sbmkq625kj1s)
- [С2С : Информация : SEO - шаблон генерации описаний](https://docs.google.com/spreadsheets/d/173ppRgTKa6nFnLUMwn-EKkwAC9DsjP7p/edit#gid=1790562973)
- [Пояснения по функционалу дизайна С2C в т.ч. новому](https://docs.google.com/document/d/1m8U2FlSQgQTpiVHWz2CZoRRZVmICzW5vBJEo93ywQkM/edit#heading=h.141s7ei40b5l)

## Ресурсы

- [Репозиторий](https://gogs.muztorg.ru/muztorg/c2c)
- [Тестовая площадка](https://dev-c2c.muztorg.ru/)
- [Разворачивание проекта в локальном окружении](https://youtrack.muztorg.ru/articles/25-A-4/Ubuntu-%D0%A0%D0%B0%D0%B7%D0%B2%D0%BE%D1%80%D0%B0%D1%87%D0%B8%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B0-%D0%B2-%D0%BB%D0%BE%D0%BA%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%BC-%D0%BE%D0%BA%D1%80%D1%83%D0%B6%D0%B5%D0%BD%D0%B8%D0%B8)

# PropertyController — управление параметрами объявлений

`App\ViewModels\PropertyController` отвечает за работу с параметрами (свойствами) объявления на основе справочников категории: генерация формы, загрузка и отображение значений, правила валидации и сохранение.

## Быстрый старт

```php
use App\ViewModels\PropertyController;
use App\Models\Category;
use App\Models\Item;

// 1) Инициализация по категории
$category = Category::findOrFail($categoryId);
$prop = new PropertyController($category);

// 2) Генерация формы (HTML блок для blade)
$propertiesHtml = $prop->render();

// 3) Загрузка значений для существующего объявления
$prop->load_values($item);

// 4) Правила валидации для Request
$rules = $prop->rules();

// 5) Сохранение значений по посту
$prop->save($item, $request->all());
```

Пример использования в контроллере см. `app/Http/Controllers/ItemController.php`:
- метод `show` — загрузка значений и выдача в представление: `fields => $prop->fields()`
- метод `make` — генерация формы создания: `properties => $properties_render->render()`
- метод `edit` — генерация формы редактирования и загрузка текущих значений
- метод `save_post` — валидация по `$properties_controller->rules()` и сохранение `$properties_controller->save(...)`

## Публичный API (основные методы)
- `__construct(Category $category)` — инициализация по категории (подтягивает связанные справочники категории).
- `render(): string` — возвращает HTML для вставки в форму (создание/редактирование объявления).
- `rules(): array` — возвращает правила валидации параметров на основе справочников.
- `load_values(Item $item): void` — подставляет текущие значения параметров из объявления.
- `fields(): array` — возвращает структуру полей для просмотра карточки объявления.
- `save(Item $item, array $payload): void` — сохраняет значения параметров в БД.

## Архитектура решения

### 1) Категория - Справочники
У каждой `Category` есть набор связанных `Dictionary` записей (через связь `category->dictionaries()`), описывающих параметры объявления: тип, ключ, возможные значения, ограничения, порядок отображения и т.д.

### 2) Оркестратор PropertyController 
`PropertyController` собирает список справочников категории и строит реестр обработчиков параметров. На основании типа справочника он выбирает соответствующий класс-обработчик из семейства `PropertyDictionary*` и делегирует ему:
- генерацию UI (HTML),
- сбор правил валидации,
- загрузку текущих значений из `Item`,
- сохранение новых значений.

Основной жизненный цикл:
1. Конструктор получает `Category`, подгружает ее `dictionaries` и вызывает `build_registery()`.
2. На каждую запись-справочник создается обработчик нужного типа и добавляется в реестр.
3. В контроллере или сервисе вызываются методы `render()`, `rules()`, `load_values()`, `save()`.

### 3) Словари-обработчики (`app/ViewModels`)
- `PropertyDictionary.php` — базовый класс, общий контракт и вспомогательная логика.
- Конкретные реализации по типам:
  - `PropertyDictionarySimple.php` — простой текст/строка.
  - `PropertyDictionaryNumber.php` — числовые поля и их ограничения.
  - `PropertyDictionaryRange.php` — диапазоны (min/max, слайдеры).
  - `PropertyDictionaryList.php` — справочные списки (select).
  - `PropertyDictionaryMulti.php` — множественный выбор (multiselect/checkbox list).
  - `PropertyDictionaryLogical.php` — логические флаги (boolean).
  - `PropertyDictionaryBinary.php` — бинарные данные/опции при необходимости.

Каждый обработчик знает:
- как представить параметр в форме (создание/редактирование),
- как валидировать входные данные (формирует часть правил для `Request`),
- как прочитать/записать значения в модели и связях.

### 4) Поток данных
- В форму (create/edit) попадает HTML блока `render()` от `PropertyController` (он склеивает блоки всех обработчиков по порядку).
- На POST:
  - Контроллер валидирует входные данные, объединяя общие правила с `$properties_controller->rules()`.
  - После успешной валидации вызывается `$properties_controller->save($item, $request->all())`.
- В карточке товара (show):
  - В контроллере выполняется `$prop->load_values($item)` и затем `fields()` передается во view для отображения.

## Рекомендации по интеграции
- Правила: объединяйте бизнес-валидацию (`name`, `price`, `description`, …) с правилами из `rules()`.
- Производительность: старайтесь грузить `Category` и ее `dictionaries` одним запросом; при необходимости добавьте `with()`.
- Расширение: для нового типа параметра создайте новый `PropertyDictionary*` класс и допишите маппинг в фабрике/реестре внутри `PropertyController::build_registery()`.
- UI: вынесите шаблоны рендеринга параметров в blade-компоненты, если потребуется переиспользование или темизация.

## Где посмотреть примеры
- `app/Http/Controllers/ItemController.php` — все этапы: render/rules/load_values/save.
- `resources/views/item/*.blade.php` — места вставки HTML блоков параметров и их отображение (см. `item.new`, `item.edit`, `item.show`).

## Частые сценарии

### Создание объявления
```php
$category = Category::findOrFail($categoryId);
$prop = new PropertyController($category);
$rules = array_merge([
    'name' => 'required',
    'price' => 'required',
], $prop->rules());

$this->validate($request, $rules);

$item = Item::create([...$request->all(), 'user_id' => auth()->id()]);
$prop->save($item, $request->all());
```

### Редактирование объявления
```php
$item = auth()->user()->items()->findOrFail($id);
$category = $item->category;
$prop = new PropertyController($category);
$prop->load_values($item);

$rules = array_merge(['name' => 'required'], $prop->rules());
$this->validate($request, $rules);

$item->fill($request->all())->save();
$prop->save($item, $request->all());
```

### Отображение в карточке товара
```php
$item = Item::findOrFail($id);
$category = $item->findCategory();
$prop = new PropertyController($category);
$prop->load_values($item);

return view('item.show', [
    'item' => $item,
    'fields' => $prop->fields(),
]);
```