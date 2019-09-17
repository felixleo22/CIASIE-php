<?php
session_start();
require_once '../../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

//Installation de twig
$container = new \Slim\Container();
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('../views');

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};


//Creation de l application
$app = new \Slim\App($container);

$app->get('/', function (Request $request, Response $response, array $args){
    return $this->view->render($response, 'index.html.twig');
});

$app->run();
