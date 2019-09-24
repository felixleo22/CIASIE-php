<?php

namespace MyApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class PersonnageController extends Controller
{
    public function Create(Request $request, Response $response, $args){
        return $this->views->render($response, 'ajoutPersonnage.html.twig');
    }
}
