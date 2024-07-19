> Все пути до загружаемых\выгружаемых файлов начинаются с **upload** директории проекта.
> Т.е у нас есть input.csv файл, нам необходимо скопировать его в {projectDir}/upload директорию. 
> Пример вызова команды в данном случае: ```php command.php -i input.csv -o output.json```.
> output.json так же появится в upload директории

## При наличии makefile у проверяющего

Развернуть проект:  ```make init```\
Запустить команду: ```make run {путь до csv} {путь до json}```\
Запустить тесты: ```make tests```

## При отсутствии makefile у проверяющего

### Развернуть проект
```bash
    docker compose up -d ## Собрать контейнер
```
```bash
    docker compose exec php-cli composer i ## Установить зависимости
```
### Запустить команду
```bash
    docker compose exec php-cli php command.php -i {путь до csv} -o {путь до json}
```
### Запустить тесты
```bash
    docker compose exec php-cli php vendor/bin/codecept run --steps
```
