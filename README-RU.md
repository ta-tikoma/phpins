#RU
# phpins
Предотвращает тупые ошибки, например: забыл подключить используемый класс

### Проверяльщики :
IDefinedThisVariable - Определяет была ли определена переменная и была ли она использована
INotForgottenAddUseThisClass - Определяет был подключен используемый класс и были ли использованы все подключенные
INotUseCyrillicLetterC - Проверяет наличие кирилических символов в переменных

### Установка

1. Установить [composer](https://getcomposer.org/).
2. Установить `phpins` используя команду:
```
composer global require ta-tikoma/phpins
```

### Пример запуска из командной строки:
```
phpins -f {file-path}
```

### Пример вывода:
```
RemoveDownLineIndex.php:
  WARNING:RemoveDownLineIndex.php:5:18:Class not use "FormData"
  WARNING:RemoveDownLineIndex.php:7:18:Class not use "EntityManagerInterface"
  WARNING:RemoveDownLineIndex.php:8:32:Class not use "Auth"
```

### Логика директорий приложения
Contracts - абстрактные классы и интерфейсы
Entities - сущности глобально используемые в приложении
Helpers - помощники
Mutators - набор мутаторов для изменения параметров файла
Validators - набор проверяльщиков. каждый должен находится так же в своей папке на случай введения объектов относящищся только к данному проверяльщику.

### Translations
[EN](https://github.com/ta-tikoma/phpins/blob/master/README.md)
[RU](https://github.com/ta-tikoma/phpins/blob/master/README-RU.md)