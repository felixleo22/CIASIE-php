<?php
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


}