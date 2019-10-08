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
        $perso = [];
        
        //upload de la photo
        $uploadedFiles = $request->getUploadedFiles();
       
        $photo = $uploadedFiles['photo'];
        if($photo->getError() === UPLOAD_ERR_OK) {
            if(!Utils::isAcceptedFile($photo)) {
                FlashMessage::flashError('Le fichier doit etre une image');
                return Utils::redirect($response, 'formCreerEntite');
            }
            $nomFichier = Utils::uploadFichier($photo);
            $perso['photo'] = $nomFichier;
        }else{
            $perso['photo'] = NULL;
        }
       
        $perso['nom'] = Utils::getFilteredPost($request, 'nom');
        $perso['prenom'] = Utils::getFilteredPost($request, 'prenom');
        $perso['type'] = Utils::getFilteredPost($request, 'type');
        $perso['taille'] = Utils::getFilteredPost($request, 'taille');
        $perso['poids'] = Utils::getFilteredPost($request, 'poids');
        $perso['pointVie'] = Utils::getFilteredPost($request, 'pointVie');
        $perso['pointAtt'] = Utils::getFilteredPost($request, 'pointAtt');
        $perso['pointDef'] = Utils::getFilteredPost($request, 'pointDef');
        $perso['pointAgi'] = Utils::getFilteredPost($request, 'pointAgi');
        if (!Utils::verifIfNumber($perso['taille']) || !Utils::verifIfNumber($perso['poids']) || 
        !Utils::verifIfNumber($perso['pointVie']) || !Utils::verifIfNumber($perso['pointAtt']) || 
        !Utils::verifIfNumber($perso['pointDef']) || !Utils::verifIfNumber($perso['pointAgi'])) {
            FlashMessage::flashError("Valeurs d'entrés incorrect");
            return Utils::redirect($response, 'listeEntites'); 
        }
   
        $entite = Entite::create($perso);
        return Utils::redirect($response, 'listeEntites');
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
    public function afficherEntite(Request $request, Response $response, $args) {
        $entite = Entite::find($request->getAttribute('id'));
        return $this->views->render($response, 'editEntite.html.twig',['entite'=>$entite]);
    }

    /**
     * 
     */
    public function modifierEntite(Request $request, Response $response, $args) {
        $id = Utils::sanitize($args['id']);
        if($id === null) return Utils::redirect($request, 'formModifEntite');
        $entite = Entite::find($id);
        $entite->type = Utils::getFilteredPost($request, "type");
        $entite->prenom = Utils::getFilteredPost($request, "prenom");
        $entite->nom = Utils::getFilteredPost($request, "nom");
        $entite->taille = Utils::getFilteredPost($request, "taille");
        $entite->poids = Utils::getFilteredPost($request, "poids");
        $entite->pointVie = Utils::getFilteredPost($request, "pointVie");
        $entite->pointAtt = Utils::getFilteredPost($request, "pointAtt");
        $entite->pointDef = Utils::getFilteredPost($request, "pointDef");
        $entite->pointAgi = Utils::getFilteredPost($request, "pointAgi");
        if (!Utils::verifIfNumber($entite->taille) || !Utils::verifIfNumber($entite->poids) || 
        !Utils::verifIfNumber($entite->pointVie) || !Utils::verifIfNumber($entite->pointAtt) || 
        !Utils::verifIfNumber($entite->pointDef) || !Utils::verifIfNumber($entite->pointAgi)) {
            FlashMessage::flashError("Valeurs d'entrés incorrect");
            return Utils::redirect($response, 'listeEntites'); 
        }
        //photo 
        $uploadedFiles = $request->getUploadedFiles();

        $photo = $uploadedFiles['photo'];
        if($photo->getError() === UPLOAD_ERR_OK) {
            if(!Utils::isAcceptedFile($photo)) {
                FlashMessage::flashError('Le fichier doit etre une image');
                return Utils::redirect($response, 'formModifEntite', ['id' => $id]);
            }
            $nomFichier = Utils::uploadFichier($photo);
            $entite->photo = $nomFichier;
        }

        $entite->save();
        return Utils::redirect($response, 'listeEntites');
    }

    /**
     * 
     */
    public function suppressionEntite(Request $request, Response $response, $args){
        $id = Utils::sanitize($args['id']);
        $entite = Entite::find($id);
        if($entite != null) {
            $entite->delete();
        }
        return Utils::redirect($response, 'listeEntites');
    }


}

