<?php

namespace MyApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use MyApp\models\Entite;

class PersonnageController extends Controller
{
    public function formulaireCreation(Request $request, Response $response, $args){
        return $this->views->render($response, 'ajoutPersonnage.html.twig');
    }

    /**
     * l'attribut photo n'est pas gere pour le moment (initialise a vide)
     */
    public function creerPersonnage(Request $request, Response $response, $args){
        $personnage = [];
        $personnage['nom'] = $request->getParsedBodyParam('nom');
        $personnage['prenom'] = $request->getParsedBodyParam('prenom');
        $personnage['type'] = $request->getParsedBodyParam('type');
        $personnage['taille'] = $request->getParsedBodyParam('taille');
        $personnage['pointVie'] = $request->getParsedBodyParam('pointVie');
        $personnage['pointAtt'] = $request->getParsedBodyParam('pointAtt');
        $personnage['pointDef'] = $request->getParsedBodyParam('pointDef');
        $personnage['pointAgi'] = $request->getParsedBodyParam('pointAgi');
        $personnage['photo'] = "";
        $entite = Entite::create($personnage);
    }
}

