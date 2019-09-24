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
     * les erreurs ne sont pas gerees pour le moment
     */
    public function creerPersonnage(Request $request, Response $response, $args){
        //upload de la photo
        $destination = '/img';
        $uploadedFiles = $request->getUploadedFiles();

        $photo = $uploadedFiles['photo'];
        if($photo->getError() !== UPLOAD_ERR_OK) { //erreur a faire plus tard }
        $nomFichier = Utils::uploadFichier($destination, $photo);


        //creation du personnage dans la bdd
        $personnage = [];
        $personnage['nom'] = $request->getParsedBodyParam('nom');
        $personnage['prenom'] = $request->getParsedBodyParam('prenom');
        $personnage['type'] = $request->getParsedBodyParam('type');
        $personnage['taille'] = $request->getParsedBodyParam('taille');
        $personnage['pointVie'] = $request->getParsedBodyParam('pointVie');
        $personnage['pointAtt'] = $request->getParsedBodyParam('pointAtt');
        $personnage['pointDef'] = $request->getParsedBodyParam('pointDef');
        $personnage['pointAgi'] = $request->getParsedBodyParam('pointAgi');
        $personnage['photo'] = $nomFichier;
        $entite = Entite::create($personnage);
    }
}

