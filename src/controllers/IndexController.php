<?php


namespace Smash\controllers;

use smash\models\Admin;
use Smash\models\Entite;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class IndexController extends Controller
{
    public function index(Request $request, Response $response){
        $this->views->render($response, 'index.html.twig', ['session' => $_SESSION]);
    }

}

