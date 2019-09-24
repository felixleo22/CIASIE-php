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
$app->get('/creer-entite', '\\MyApp\\controllers\\EntiteController:formulaireCreation');

//ajout dans la bdd
$app->post('/creer-entite', '\\MyApp\\controllers\\EntiteController:creerEntite');

//affichages des entites
$app->get('/liste-entite', '\\MyApp\\controllers\\EntiteController:listeEntite');

//affichages des admins
$app->get('/liste-admin', '\\MyApp\\controllers\\AdminController:listeAdmin');

//accès au formulaire d'édition
$app->get('/modifier-admin/{id}', '\\MyApp\\controllers\\AdminController:formulaireEditAdmin');

//modification des admins dans la bdd
$app->post('/verifEdit', '\\MyApp\\controllers\\AdminController:verifAdmin');

//suppression des admins dans la bdd
$app->post('/supprimer-admin', '\\MyApp\\controllers\\AdminController:suppressionAdmin');



//Classement
$app->get('/classement', '\\MyApp\\controllers\\LadderController:index');

/** Lancement de l'application */

$app->get('/login', '\\MyApp\\controllers\\LoginController:index');
$app->post('/login', '\\MyApp\\controllers\\LoginController:login');
$app->get('/deconnect', '\\MyApp\\controllers\\LoginController:deconnect');

$app->run();
