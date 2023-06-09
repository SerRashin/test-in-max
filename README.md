# Тестовое задание в Max

Написать сервис сокращения ссылок на Yii2.

> Пример: https://tinyurl.com/app

- UI не нужен, достаточно JSON API.
- БД любая (Maria, Mysql etc.).
- Описание развертывания и работы проекта.
- Возможность запустить проект через докер.
- Покрытие тестами.
- Документирование кода, например через Swagger.
- Код выложить на GitHub.

# Реализация
## Принцип работы
Ни один из методов криптографии не обеспечит защищенность от коллизий(так как строка у нас должна быть минимальна).

Для этого выбран один из самых простых способов, преобразование идентификатора Link:id записи в base62 который и гарантирует что пересечения не возможны.
По сути мы просто представляем настоящий ID в виде 64-ричной системы исчисления.

Так как base62 это метод криптографии с обратимым преобразованием, не имеет смысла хранить hash в БД, т.к. быстрее и менее затратно по ресурсам будет вычислять значение на лету.
Преобразование на лету занимает менее наносекунды для числа 50 000 000 000.

В реализации есть rest-api для добавления/просмотра ссылок, а так же механизм редиректа непосредственно.

## Архитектура
В отличие от стандартной модели Yii в проект добавлены три дополнительные директории:
dto, services и repositories.

`services` - содержит все сервисы с БЛ. (ShortenerService - кандидат в приватный вендор).   
`repositories` - нужен как минимум по двум причинам, для абстракции над БД и для того, чтобы покрыть правильными unit тестами.   
`dto` - DTO для передачи данных в сервисный слой, реализованы на основе `Model` т.к. нет другого способа валидации средствами Yii.   

## Развертывание

Для равзвертывания используйте команду

```shell
make start-{env}
```

> {env} - один из параметров - dev/prod/test

Например для запуска всех тестов, выполните команду

```shell
make start-test
```

> Список всех команд смотрите в Makefile

Для запуска в prod режиме необходима БД поэтому оставил storage из dev. 
Для прода это некорректно но для теста сойдет.
