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


    public function isAlive($entite) {
        $res = false;
        if ($entite->pointVie > 0) {
            $res = true;
        }
        return $res;
    }
}