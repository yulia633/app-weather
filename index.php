<?php

require 'vendor/autoload.php';

// Создать новый контейнер с внедренным Guzzle
$container = new \Slim\Container([
'http' => function () {
    return new GuzzleHttp\Client();
}
]);

// Создать объект App
$app = new \Slim\App($container);

// Получить погоду по идентификатору местоположения
$app->get('/locations/{id}', function ($request, $response, $args) {
    // Получить погоду из MetaWeather
    $result = $this->http->get("https://www.metaweather.com/api/location/{$args['id']}")
    ->getBody()
    ->getContents();
   // Вернуть результаты в виде JSON
    return $response->withStatus(200)->withJson(json_decode($result));
});

$app->delete('/locations/{id}', function ($request, $response, $args) {
    return $response->withStatus(200)->write("Удалено местоположение {$args['id']}.
    ");
});

// Запуск приложения
$app->run();
