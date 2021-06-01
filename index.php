<?php

require 'vendor/autoload.php';

// Создать контейнер
$container = new \Slim\Container([
    // Добавить в контейнер Guzzle под идентификатором http
    'http' => function () {
        return new GuzzleHttp\Client();
    },
    // Добавить в контейнер mysqli под идентификатором mysql
    'mysql' => function () {
        $mysqli = new mysqli(
            getenv('DATABASE_HOST'),
            getenv('DATABASE_USER'),
            getenv('DATABASE_PASSWORD'),
            getenv('DATABASE_NAME')
        );
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL {$mysqli->connect_error}";
            exit;
        } else {
            return $mysqli;
        }
    },
]);

// Создать объект App
$app = new \Slim\App($container);
// Получить погоду по идентификатору местоположения
$app->get('/locations/{id}', function ($request, $response, $args) {
    // Получить местоположение из базы данных
    $id = $this->mysql->real_escape_string($args['id']);
    $results = $this->mysql->query("SELECT * FROM locations WHERE id='{$id}'");
    // Если местоположение найдено, тогда получить прогноз погоды из БД, в противном случае сделать запрос к MetaWeather
    if ($results->num_rows > 0) {
        $result = $results->fetch_assoc()['weather'];
    } else {
        $result = $this->http->get("https://www.metaweather.com/api/location/{$id}")
        ->getBody()
        ->getContents();
        $cleanResult = $this->mysql->real_escape_string($result);
        if (
            !$this->mysql->query("INSERT into locations (id, weather) VALUES ('{$id}', '{$cleanResult}')")
        ) {
            throw new Exception("Местоположение не может быть обновлено.");
        }
    }
    // Возвратить результаты в формате JSON
    return $response->withStatus(200)->withJson(json_decode($result));
});

$app->delete('/locations/{id}', function ($request, $response, $args) {
    // Получить местоположение из базы данных
    $id = $this->mysql->real_escape_string($args['id']);
    $results = $this->mysql->query("SELECT * FROM locations WHERE id='{$id}'");
    // Если существует местоположение, удалить местоположение, иначе отправить ответ
    // с кодом состояния 404
    if ($results->num_rows > 0 && $this->mysql->query("DELETE FROM locations WHERE id='{$id}'")) {
        return $response->withStatus(200)->write("Удалено местоположение {$args['id']}.");
    } else {
        return $response->withStatus(404)->write("Местоположение {$args['id']} не найдено.");
    }
});

// Запуск приложения
$app->run();
