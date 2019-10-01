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

    public function combat($p, $m) {
        $personnage = $p;
        $montre = $m;
    }

    /**
     * choix d'un ramdom selon l'agilite de chaque entite
     * le plus grand chiffre commence a attaque
     * @return Entite
     */
    public function choixAttaquant(){
        $res = $monstre;
        $val1 = 0; $val2 = 0;
        while ($val1 === $val2) {
            $val1 = mt_rand(0,$personnage->pointAgi);
            $val2 = mt_rand(0,$monstre->pointAgi);
        }
        if($val1 > $val2) {
            $res = $personnage;
        }
        return $res;
    }

    /**
     * retourne la victime selon l'attaquant entre en parametre
     * @return Entite
     */
    public function getVictime($attaquant) {
        $res = $monstre;
        if ($attaquant->type === 'monstre') {
            $res = $personnage;
        }
        return $res;
    }

    /**
     * @return boolean
     */
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

    public function play(Response $response){
        while(isAlive($personnage) || isAlive($monstre)) {
            $attaquant = choixAttaquant();
            $victime = getVictime($attaquant);
            $degat($attaquant, $victime);
            // pause
        }
    }


}