<?php

require 'vendor/autoload.php';

$app = new \Slim\App();

$app->get('/locations/{id}', function ($request, $response, $args) {
    return $response->withStatus(200)->write("Получено местоположение {$args['id']}.
    ");
});

$app->delete('/locations/{id}', function ($request, $response, $args) {
    return $response->withStatus(200)->write("Удалено местоположение {$args['id']}.
    ");
});

$app->run();
