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
        $entities = Entite::all();
        $personnages = Utils::filter($entities, "personnage");
        $monstres = Utils::filter($entities, "monstre");
        return $this->views->render($response, 'index.html.twig', ['personnages' => $personnages, 'monstres' => $monstres],$_SESSION);
    }

}

