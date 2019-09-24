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
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container['db'] = function ($container) use ($capsule){
    return $capsule;
};

$app = new Slim\App($container);


/** Routes */

//Root
$app->get('/', '\\MyApp\\controllers\\IndexController:index') -> setName('accueil');

//Login
$app->get('/connexion[/{username}]', '\\MyApp\\controllers\\LoginController:index');

//Formulaire creation entite
$app->get('/entite/creer', '\\MyApp\\controllers\\EntiteController:formulaireCreation');

//Ajout dans la bdd
$app->post('/entite/creer', '\\MyApp\\controllers\\EntiteController:creerEntite');

//Affichage des entites
$app->get('/entite/liste', '\\MyApp\\controllers\\EntiteController:listeEntite');

//Affichage des admins
$app->get('/admin/liste', '\\MyApp\\controllers\\AdminController:listeAdmin');

//Modification des admins dans la bdd
$app->get('/admin/modifier/{id}', '\\MyApp\\controllers\\AdminController:formulaireEditAdmin');

//Suppression des admins dans la bdd
$app->post('/admin/supprimer', '\\MyApp\\controllers\\AdminController:suppressionAdmin');

//Classement
$app->get('/classement', '\\MyApp\\controllers\\LadderController:index');

/** Lancement de l'application */

$app->get('/login', '\\MyApp\\controllers\\LoginController:index');
$app->post('/login', '\\MyApp\\controllers\\LoginController:login');
$app->get('/deconnect', '\\MyApp\\controllers\\LoginController:deconnect');

$app->run();
