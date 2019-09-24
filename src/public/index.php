<?php
session_start();
require_once('../../vendor/autoload.php');
require_once('../config/config.inc.php');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

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
$capsule = new Capsule;
$capsule->addConnection([$container['settings']['db']]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function ($container) use ($capsule){
    return $capsule;
};

$app = new Slim\App($container);


/** Routes */

//Root
$app->get('/', '\\MyApp\\controllers\\IndexController:index');

//Login
$app->get('/connexion[/{username}]', '\\MyApp\\controllers\\LoginController:index');

//Création personnage
$app->get('/creation-personnage', '\\MyApp\\controllers\\PersonnageController:create');

//Classement
$app->get('/classement', '\\MyApp\\controllers\\LadderController:index');

/** Lancement de l'application */

$app->run();
