<?php

// DIC configuration

$container = $app->getContainer();


$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $data = [
            'status' => 'error',
            'code'  => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];
        return $response->withJson($data, $exception->getCode());
    };
};
