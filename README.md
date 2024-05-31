# What I did

## Used PostgreSQL as a database.

## Created migration classes to create tables `currencies`, `users`, `products`, `coupons`. Though we might use some bundle for currencies.

### A user may have base currency, but every product also has its currency.
### Products belong to users.
### A coupon may belong to a user or be common for all users (eg a promotion by the system).

## Created entities with necessary relations for tables listed above.

## Created fixture classes to insert some dummy data into tables for testing.

## Created BaseController for common methods between all controllers and put there a validation method.

## Created 2 validation request classes, the second extends the first as only the difference is one property of body. Later, if it's necessary, they can be separated. We also can add validation groups to used constraints to keep their working order, because Symfony doesn't use the order they're listed above the field in.

## Created 3 custom constraints for validation.

### Product should be active and should belong to an active user who has verified his/her email address.
### Coupon should have valid status and belong to the same user whom the product belongs to or be common for all users.
### To validate tax number and get tax rates I used bundle "ibericode/vat-bundle". They have a constraint to check tax number existence, I created a custom constraint which checks only the tax number format. That's more comfortable to use for our testing purposes. An alternative way would be storing data in our database and write all the logic customly, but the bundle is enough flexible and updates its data regularly, so think better to use it for now.

## Created 5 service classes (which are used inside each other and outside of them) to separate business logic there.

### To calculate discount percent for the coupon.
### To get a product with its currency.
### To calculate tax rate based on tax number.
### To calculate product price based on product ID, coupon code and tax number.
### To make a purchase.

## The task says that there are classes `PaypalPaymentProcessor` and `StripePaymentProcessor` which should be used on purchase and won't be modified, but those classes are actually missing from the project probably by a mistake. So I left that part of the code and just return success message in the according endoint response.

## Kept payment methods in some config class, but a better way could be storing of processors in db and having a status column for them, that admins can turn on/off necessary processor from admin panel immediately without asking programmers to make changes in codes. Also by that way it'll be easy to list available payment methods with their proper names and logos.

## Wrote just 1 test class for demonstration.

## Didn't translate error and success messages due to time.

## Added @todo comments to some places of codes where they could be improved but haven't been done due to time.

## I did refactoring during the development, so if you check pull requests separately and don't love some codes, please check also their final looks and places.

-----------------------------------------------

# Task

# Написать Symfony REST-приложение для расчета цены продукта и проведения оплаты

Необходимо реализовать 2 эндпоинта:
1. POST: для расчёта цены

http://127.0.0.1:8337/calculate-price

Пример JSON тела запроса:
```
{
    "product": 1,
    "taxNumber": "DE123456789",
    "couponCode": "D15"
}
```
2. POST: для осуществления покупки

http://127.0.0.1:8337/purchase

Пример JSON тела запроса:
```
{
    "product": 1,
    "taxNumber": "IT12345678900",
    "couponCode": "D15",
    "paymentProcessor": "paypal"
}
```

При успешном выполнении запроса следует возвращать HTTP ответ с кодом 200.

При неверных входных данных или ошибках оплаты следует возвращать HTTP ответ с кодом 400 и JSON объект с описанием ошибок.

## Продукты
Продукты предполагается хранить в БД. В качестве примера можно использовать 3 продукта:
- Iphone (100 евро)
- Наушники (20 евро)
- Чехол (10 евро)

## Купоны
Купоны позволяют применить скидку к покупке и могут быть двух типов:
- фиксированная сумма скидки
- процент от суммы покупки

Предполагается, что купоны создаются продавцом и хранятся в системе. Например, при наличии купонов P10 (скидка 10%) и P100 (скидка 100%) покупатель не должен иметь возможности применить несуществующий купон P50.

Формат кода купона вы можете выбрать на своё усмотрение.

## Расчет налога
Налог рассчитывается исходя из страны налогового номера и прибавляется к цене продукта:
- Германия - 19%
- Италия - 22%
- Франция - 20%
- Греция - 24%

Например, цена Iphone для покупателя из Греции составит 124 евро (100 евро + налог 24%). Если у покупателя есть купон на 6% скидку, то цена будет 116.56 евро (100 евро - 6% скидка + налог 24%).

## Формат налогового номера
Форматы налоговых номеров для разных стран:
- DEXXXXXXXXX - Германия
- ITXXXXXXXXXXX - Италия
- GRXXXXXXXXX - Греция
- FRYYXXXXXXXXX - Франция

где X - любая цифра от 0 до 9, Y - любая буква. Длина налогового номера различается в зависимости от страны.

## Начало работы
Для удобства выполнения и проверки задания предлагаем вам создать репозиторий, используя данный репозиторий, как шаблон (не форкайте его!).  
Это даст доступ к базовому Docker-контейнеру с необходимыми для задания компонентами. Для установки выполните `make init`, что запустит Symfony-приложение на порту 8337, смонтировав текущую директорию в контейнер.

Имейте в виду: контейнер предназначен для Linux и Mac. Для Windows потребуется WSL. Работая с нами, вы будете использовать контейнеры с аналогичными требованиями.

## Детали выполнения
При выполнении задания необходимо:
- реализовать валидацию всех полей запроса, включая корректность налогового номера, используя Symfony Validator
- рассчитать итоговую цену с учетом купона (если применим) и налога
- использовать `PaypalPaymentProcessor::pay()` или `StripePaymentProcessor::processPayment()` для проведения платежа. Эти классы представлены в проекте и не должны модифицироваться.
- обновить `requests.http`, демонстрируя логику работы вашего приложения.

CRUD для сущностей предполагается уже реализованным, дополнительные проверки в сервисах не требуются.

По завершении отправьте ссылку на репозиторий.

Учитывайте возможность добавления новых платежных процессоров.

Если какая-то часть задания кажется сложной, выберите простейшее решение и комментируйте альтернативные подходы, которые рассматривали.

### Будет плюсом
- Расширенная контейнеризация
- Использование сущностей, PostgreSQL/MySQL
- Наличие PHPUnit тестов
- Соответствие кода принципам SOLID
- Поэтапное оформление коммитов
- Показать способность избегать сложных подходов вроде onion-based/DDD/CQS/гексагональной архитектуры в пользу корректности и полноты решения.
