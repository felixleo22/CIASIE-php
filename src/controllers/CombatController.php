<?php

namespace Smash\controllers;
use Smash\models\Admin;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use smash\models\Entite;


class CombatController extends Controller {

    protected $personnage;
    protected $monstre;

    public function creerCombat(Request $request, Response $response, $args) {
        $montre = Entiete::find(Utils::getFilteredPost('idMonstre'));
        $personnage = Entite::find(Utils::getFilteredPost('idPersonnage'));
        //TODO verifier les types
        $combat = new Combat();
        $combat->idPersonnage = $personnage;
        $combat->idMonstre = $monstre;
        $combat->pointVieMonstre = $monstre->pointVie;
        $combat->pointViePersonnage = $personnage->pointVie;

        $idCombat = $combat->save();
        return $response->withJson($idCombat);
    }

    /**
     * Permet de retourner un random pour le system d'ordre pour attaquer
     * @param Entite $entite
     * @return int
     */
    public function generateRandom(Entite $entite){
        $agilite = $entite->getAttribute('pointAgi');
        return mt_rand(0,$agilite);
    }


    public function play(Entite $entite1, Entite $entite2, Response $response){
        while($entite1->getAttribute('pointVie')> 0  || $entite2->getAttribute('pointVie') > 0){
            if ($this->generateRandom($entite1) < $this->generateRandom($entite2)){
                //fonction pour infliger des dégats
                $generate = $this->generateRandom($entite1);
                return Utils::redirect($response, 'combat');
            }
        }
        return  Utils::redirect($response, 'accueil');

    }


    public function isAlive($entite){

        $res = false;
        if ($entite->pointVie > 0) {
            $res = true;
        }
        return $res;
    }

    /**
     * point d'attaque choisit aléatoirement entre 80% et 120% de l'attaquant
     * l'attaquant à 5% d'effectuer un coup critique (degat multiplie par 2)
     * la défence est augmenter de 1% du poids de la victime
     * la victime à 5% d'esquiver le coup 
     */
    public function degat($attaquant, $victime) {
         $esquive = $mt_rand(1, 100);
         if ($esquive >= 5) {
             $res = 0;
         } else { 
            $critique = $mt_rand(1, 100);
            if ($critique >= 5) {
                $critique = 2;
            } else {
                $critique = 1;
            }
            $att = $mt_rand(0.8, 1.2);
            $defSup = (0.01*$victime->poids);
            $res = ($attaquant->pointAtt*$att)*$critique - $victime->pointDef+$defSup;
         }
         return $res;
    }

}