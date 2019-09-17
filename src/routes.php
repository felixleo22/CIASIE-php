<?php

session_start();
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../vendor/autoload.php';

//Initialisation de twig
$container = new \Slim\Container();
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('../views');

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

$app = new \Slim\App($container);


/** Routes */

// Racine
$app->get('/', '\\MyApp\\controllers\\TestController:index');

// Login
$app->get('/login[/{defaultUsername}]', '\\MyApp\\controllers\\LoginController:index');
