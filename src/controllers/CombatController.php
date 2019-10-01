<?php
<<<<<<< HEAD

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

=======
namespace Smash\controllers;
use Smash\models;

class Combat extends Controller {

    protected $personnage;
    protected $monstre;

    public function combat($p, $m) {
        $personnage = $p;
        $montre = $m;
    }

    /**
     * point d'attaque choisit aléatoirement entre 80% et 120% de l'attaquant
     * la défence est augmenter de 1% du poids de la victime
     */
    public function degat($attaquant, $victime) {
         $att = $mt_rand(0.8, 1.2);
         $defSup = (0.01*$victime->poids);
         $res = $attaquant->pointAtt*$att - $victime->pointDef+$defSup;
         return $res;
    }


    public function isAlive($entite){
        $res = false;
        if ($entite->pointVie > 0) {
            $res = true;
        }
        return $res;
    }


>>>>>>> 56584f0d2090bee67e82102d8a4f393ebc97bc7d
}