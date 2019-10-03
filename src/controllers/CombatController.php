<?php

namespace Smash\controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Smash\models\Entite;

use Smash\models\Combat;

class CombatController extends Controller {

    public function creerCombat(Request $request, Response $response, $args) {
        $mId = Utils::getFilteredPost($request, 'idMonstre');
        $pId = Utils::getFilteredPost($request, 'idPersonnage');
        $m = Entite::find($mId);
        $p = Entite::find($pId);

        if($m == null || $p == null) {
             return $response->withJson('ta mare');
        }
        //TODO verifier les types
        $combat = [];
        $combat['idPersonnage'] = $p->id;
        $combat['idMonstre'] = $m->id;
        $combat['pointVieMonstre'] = $m->pointVie;
        $combat['pointViePersonnage'] = $p->pointVie;

        $created = Combat::create($combat);
        return $response->withJson($created);
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

    public function choixPerso(Request $request, Response $response) {
        $data = $_POST['ids'];
        $personnage1 = Entite::find(intval($data[1]));
        $personnage2 = Entite::find(intval($data[2]));
        return $this->views->render($response, 'combat.html.twig',['personnage1'=> $personnage1,'personnage2'=> $personnage2]);
    }


//    public function play($entite1, $entite2, Response $response){
//        while($entite1->getAttribute('pointVie')> 0  || $entite2->getAttribute('pointVie') > 0){
//            if ($this->generateRandom($entite1) < $this->generateRandom($entite2)){
//                //fonction pour infliger des dégats
//                $generate = $this->generateRandom($entite1);
//                return Utils::redirect($response, 'combat');
//            }
//        }
//        return  Utils::redirect($response, 'accueil');
//
//    }

//
//    public function isAlive($entite){
//
//        $res = false;
//        if ($entite->pointVie > 0) {
//            $res = true;
//        }
//        return $res;
//    }
//
//    /**
//     * point d'attaque choisit aléatoirement entre 80% et 120% de l'attaquant
//     * l'attaquant à 5% d'effectuer un coup critique (degat multiplie par 2)
//     * la défence est augmenter de 1% du poids de la victime
//     * la victime à 5% d'esquiver le coup
//     */
//    public function degat($attaquant, $victime) {
//         $esquive = $mt_rand(1, 100);
//         if ($esquive >= 5) {
//             $res = 0;
//         } else {
//            $critique = $mt_rand(1, 100);
//            if ($critique >= 5) {
//                $critique = 2;
//            } else {
//                $critique = 1;
//            }
//            $att = $mt_rand(0.8, 1.2);
//            $defSup = (0.01*$victime->poids);
//            $res = ($attaquant->pointAtt*$att)*$critique - $victime->pointDef+$defSup;
//         }
//         return $res;
//    }
//

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