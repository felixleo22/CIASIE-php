<?php
session_start();
require_once '../../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app = new \Slim\App;

$app->get('/[hello[/{name}]]', function (Request $request, Response $response, array $args) {
    if (isset($args['name'])){
        $name = $args['name'];
        $response->getBody()->write("Hello, ${name}!");
    }
    else{
        $response->getBody()->write("Hello world!");
    }

    return $response;
});

$app->run();
