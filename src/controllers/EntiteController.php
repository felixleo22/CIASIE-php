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
        return $this->views->render($response, 'formEntite.html.twig');
    }

    /**
     * recupére les donnees du post, envoie a la bdd
     * l'attribut photo n'est pas gere pour le moment (initialise a vide)
     */
    public function creerEntite(Request $request, Response $response, $args){
        $perso = [];
        
        //upload de la photo
        $destination = '../public/img';
        $uploadedFiles = $request->getUploadedFiles();
       
        $photo = $uploadedFiles['photo'];
        if($photo->getError() === UPLOAD_ERR_OK) {
            $nomFichier = Utils::uploadFichier($destination, $photo);
            $perso['photo'] = $nomFichier;
        }else{
            $perso['photo'] = NULL;
        }
       
        $perso['nom'] = Utils::getFilteredPost($request, 'nom');
        $perso['prenom'] = Utils::getFilteredPost($request, 'prenom');
        $perso['type'] = Utils::getFilteredPost($request, 'type');
        $perso['taille'] = Utils::getFilteredPost($request, 'taille');
        $perso['pointVie'] = Utils::getFilteredPost($request, 'pointVie');
        $perso['pointAtt'] = Utils::getFilteredPost($request, 'pointAtt');
        $perso['pointDef'] = Utils::getFilteredPost($request, 'pointDef');
        $perso['pointAgi'] = Utils::getFilteredPost($request, 'pointAgi');
   
        $entite = Entite::create($perso);
        return Utils::redirect($response, 'listeEntites');
    }

    /**
     * Filtre une liste d'entité selon le type passé en paramètre
     * @param array $tab - La table que l'on souhaite filtrer
     * @param string $type - Le type selon lequel on filtre
     * @return array $res - Une nouvelle table filtrée
     */
    private static function filter($tab, $type){
        $res = [];
        foreach ($tab as $entite){
            if ($entite->type == $type ){
                $res[] = $entite;
            }
        }
        return $res;
    }

    /**
     * selectionne toute les entites de la bdd et les affiche
     */
    public function listeEntite(Request $request, Response $response, $args) {
        $listeEntite = Entite::all();
        $personnages = self::filter($listeEntite, "personnage");
        $monstres = self::filter($listeEntite, "monstre");
        return $this->views->render($response, 'affichageEntite.html.twig', [
            'personnages' => $personnages,
            'monstres' => $monstres
            ]
        );
    }

    /**
     * 
     */
    public function afficherEntite(Request $request, Response $response, $args) {
        //TODO Verifier connexion de l'utilisateur
        $entite = Entite::find($request->getAttribute('id'));
        return $this->views->render($response, 'formEntite.html.twig',['entite'=>$entite]);
    }

    /**
     * 
     */
    public function modifierEntite(Request $request, Response $response, $args) {
        //TODO Verifier connexion de l'utilisateur
        $id = Utils::sanitize($args['id']);
        if($id === null) return Utils::redirect($request, 'formModifEntite');
        $entite = Entite::find($id);
        $entite->type = Utils::getFilteredPost($request, "type");
        $entite->prenom = Utils::getFilteredPost($request, "prenom");
        $entite->nom = Utils::getFilteredPost($request, "nom");
        $entite->taille = Utils::getFilteredPost($request, "taille");
        $entite->pointVie = Utils::getFilteredPost($request, "pointVie");
        $entite->pointAtt = Utils::getFilteredPost($request, "pointAtt");
        $entite->pointDef = Utils::getFilteredPost($request, "pointDef");
        $entite->pointAgi = Utils::getFilteredPost($request, "pointAgi");

        //photo 
        $destination = '../public/img';
        $uploadedFiles = $request->getUploadedFiles();

        $photo = $uploadedFiles['photo'];
        if($photo->getError() === UPLOAD_ERR_OK) {
            $nomFichier = Utils::uploadFichier($destination, $photo);
            $perso['photo'] = $nomFichier;
        }

        $entite->save();
        return Utils::redirect($response, 'listeEntites');
    }

    /**
     * 
     */
    public function suppressionEntite(Request $request, Response $response, $args){
        //TODO Verifier connexion de l'utilisateur
        $id = Utils::sanitize($args['id']);
        $entite = Entite::find($id);
        if($entite != null) {
            $entite->delete();
        }
        return Utils::redirect($response, 'listeEntites');
    }


}

