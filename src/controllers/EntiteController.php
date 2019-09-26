<?php

namespace Smash\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Smash\models\Entite;

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
        $destination = '../public/img';
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
        $perso['photo'] = $nomFichier;
        $entite = Entite::create($perso);
        return Utils::redirect($response, 'accueil');
    }

    /**
     * selectionne toute les entites de la bdd et les affichent
     */
    public function listeEntite(Request $request, Response $response, $args) {
        $listeEntite = Entite::all();
        return $this->views->render($response, 'affichageEntite.html.twig', ['entites' => $listeEntite]);
    }

    /**
     * 
     */
    private function recupererEntite(Request $request, Response $response, $args){
        $idEntite = intval($args['id']);
        return Entite::find($idEntite);
    }
    
    /**
     * 
     */
    public function afficherEntite(Request $request, Response $response, $args) {
        $entite = Entite::find($request->getAttribute('id'));
        return $this->views->render($response, 'editEntite.html.twig',['entite'=>$entite]);
    }

    /**
     * 
     */
    public function modiferEntite(Request $request, Response $response, $args) {
        $entite->type = Utils::getFilteredPost($request, "type");
        $entite->prenom = Utils::getFilteredPost($request, "prenom");
        $entite->nom = Utils::getFilteredPost($request, "nom");
        $entite->taille = Utils::getFilteredPost($request, "taille");
        $entite->pointVie = Utils::getFilteredPost($request, "pointVie");
        $entite->pointAtt = Utils::getFilteredPost($request, "pointAtt");
        $entite->pointDef = Utils::getFilteredPost($request, "pointDef");
        $entite->pointAgi = Utils::getFilteredPost($request, "pointAgi");
        $entite->photo = Utils::getFilteredPost($request, "photo");
        $entite = Entite::save();
        return Utils::redirect($response, '/entite/liste');
    }

    /**
     * 
     */
    public function suppressionEntite(Request $request, Response $response, $args){
        $entite = recupererEntite($request, $response, $args);
        $entite::where('id',intval($args['id']))->delete();
        return $response->withRedirect('/entite/liste');
    }


}

