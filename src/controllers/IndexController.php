<?php


namespace Smash\controllers;

use Smash\models\Entite;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class IndexController extends Controller
{
    public function index(Request $request, Response $response){
        $personnages = Entite::all();
        return $this->views->render($response, 'index.html.twig', ['personnages' => $personnages],$_SESSION);
    }

}

