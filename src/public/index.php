<?php
session_start();
require_once('../../vendor/autoload.php');
require_once('../config/config.inc.php');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

//Controleurs
use Smash\controllers\IndexController;
use Smash\controllers\LoginController;
use Smash\controllers\EntiteController; 
use Smash\controllers\AdminController;
use Smash\controllers\LadderController;

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
$app->get('/', IndexController::class.':index') -> setName('accueil');

//Login
$app->get('/connexion[/{username}]', LoginController::class.':index');

//Formulaire creation entite
$app->get('/entite/creer', EntiteController::class.':formulaireCreation');

//Ajout dans la bdd
$app->post('/entite/creer', EntiteController::class.':creerEntite');

//Affichage des entites
$app->get('/entite/liste', EntiteController::class.':listeEntite');

//Affichage d'une entite
$app->get('/entite/modifier/{id}', EntiteController::class.':afficherEntite');

//Modification d'une entite dans la bdd
$app->post('/entite/modifier/{id}', EntiteController::class.':modiferEntite');

//Suppression d'une entite dans la bdd
$app->post('/entite/supprimer', EntiteController::class.':suppressionEntite');

//Affichage des admins
$app->get('/admin/liste', AdminController::class.':listeAdmin');

//Modification des admins dans la bdd
$app->get('/admin/modifier/{id}', AdminController::class.':formulaireEditAdmin');

//Suppression des admins dans la bdd
$app->post('/admin/supprimer', AdminController::class.':suppressionAdmin');

//Classement
$app->get('/classement', LadderController::class.':index');

/** Lancement de l'application */
$app->get('/login', LoginController::class.':index');
$app->post('/login', LoginController::class.':login');
$app->get('/deconnect', LoginController::class.':deconnect');

$app->run();
