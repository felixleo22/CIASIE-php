<?php

namespace MyApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use MyApp\models\Entite;

class EntiteController extends Controller
{
    /**
     * affiche le formulaire de creation de monstre via un fichier twig
     */
    public function formulaireCreation(Request $request, Response $response, $args){
        return $this->views->render($response, 'ajoutEntite.html.twig');
    }

    /**
     * recupére les donnees du post, envoie a la bdd
     * l'attribut photo n'est pas gere pour le moment (initialise a vide)
     */
    public function creerEntite(Request $request, Response $response, $args){
        //upload de la photo
        $destination = '/img';
        $uploadedFiles = $request->getUploadedFiles();

        $photo = $uploadedFiles['photo'];
        if($photo->getError() !== UPLOAD_ERR_OK) { /*erreur a faire plus tard */}
        $nomFichier = Utils::uploadFichier($destination, $photo);

        $perso = [];
        $perso['nom'] = $request->getParsedBodyParam('nom');
        $perso['prenom'] = $request->getParsedBodyParam('prenom');
        $perso['type'] = $request->getParsedBodyParam('type');
        $perso['taille'] = $request->getParsedBodyParam('taille');
        $perso['pointVie'] = $request->getParsedBodyParam('pointVie');
        $perso['pointAtt'] = $request->getParsedBodyParam('pointAtt');
        $perso['pointDef'] = $request->getParsedBodyParam('pointDef');
        $perso['pointAgi'] = $request->getParsedBodyParam('pointAgi');
        $perso['photo'] = "";
        $entite = Entite::create($perso);
    }

    /**
     * selectionne toute les entites de la bdd et les affichent
     */
    public function listeEntite(Request $request, Response $response, $args){
        $listeEntite = Entite::all();
        return $this->views->render($response, 'affichageEntite.html.twig', ['entites' => $listeEntite]);
    }

}

