<?php

namespace Smash\controllers;
use Smash\models\Admin;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use smash\models\Entite;


class CombatController extends Controller {

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

}