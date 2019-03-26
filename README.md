aQiwiDonate
===========

Принятие донат-платежей на сервере без посредников и комиссий. Построено на Qiwi API\
[Получить токен Qiwi API](https://qiwi.com/api)

![Пример работы плагина](https://media.giphy.com/media/1n75nSmSJlKz8C3Epq/giphy.gif)
[Посмотреть gif]()

## Настройка
### Конфиг
```yaml
# Счет киви, телефон в международном формате без +
phone: '71112223344'
token: '' # Получить токен тут: https://qiwi.com/api
```

### Список товаров
Настраивается в [donate.yml](./resources/donate.yml)
```yaml
- name: 'Золотишко' # Название
  description: 'Пожертвовать рублик и сообщить об этом всему серверу'
  price: 1
  command: 'say {username} проверил работу доната и подарил нам рублик :з'
  image: 'https://d12swbtw719y4s.cloudfront.net/images/G2xFvdDN/AoBw1U8Iva73d08P3d3K/Gold.fw.jpeg?w=265'
- name: 'awesome'
  description: 'Можно\n§aдаже так'
  price: 1
  command: 'say {username} убедился в работе'
```

### Настройка сообщений
Находится в [message.yml](./resources/message.yml)

### История платежей
На данный момент доступна только в файле __history.json__