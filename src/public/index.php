<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../config/config.inc.php';
require_once('../config/boostrap.php');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$container["settings"] = $config;

//Installation de twig
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('../views', []);

    //Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};

//Eloquent
$container['db'] = function ($container) use ($capsule){
    return $capsule;
};

$app = new Slim\App($container);


//Routes
$app->get('/', '\\MyApp\\controllers\\IndexController:index');

$app->get('/login', '\\MyApp\\controllers\\LoginController:index');
