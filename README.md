# simple-api

### Инструкция по разворачиванию

```
cp .env.example .env
composer install
docker-compose up -d
```

C помощью ```docker ps``` узнаем id контейнеров

Заходим в контейнер с ```mysql```,<br/>
создаем базу для проекта,<br/>
указываем название базы в ```.env```

Заходим в контейнер с ```php```,<br/>
выполняем миграции для создания таблицы объявлений<br/>
```php database/migration.php```