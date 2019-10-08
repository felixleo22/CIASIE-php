<?php

namespace Smash\controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Smash\models\Entite;

use Smash\models\Combat;

class CombatController extends Controller {
    
    public function creerCombat(Request $request, Response $response, $args) {
        $data = Utils::getFilteredPost($request, 'ids');
        $personnageArray = [];
        $monstreArray = [];
        foreach ($data as $idEntite) {
            $entite = Entite::find($idEntite);
            if($entite === null) {
                FlashMessage::flashError('Une ou plusieurs des entités séléctionnées n\'existent pas');
                return Utils::redirect($response, 'accueil');
            }
            
            if($entite->type === "monstre") {
                array_push($monstreArray, $entite);
            }else{
                array_push($personnageArray, $entite);
            }
        }
        
        //TODO a changer lorsqu'il y  aura du 2v2
        if(count($personnageArray) !== 1 && count($monstreArray) !== 1) {
            FlashMessage::flashError('Vous devez choisir un personnage et un monstre');
            return Utils::redirect($response, 'accueil');
        }
        
        //TODO verifier les types
        $combat = new Combat();
        $combat->idPersonnage = $personnageArray[0]->id;
        $combat->idMonstre = $monstreArray[0]->id;
        $combat->pointVieMonstre = $monstreArray[0]->pointVie;
        $combat->pointViePersonnage = $personnageArray[0]->pointVie;
        
        $created = $combat->save();
        
        if(!$created) {
            FlashMessage::flashError('Impossible de créer le combat');
            return Utils::redirect($response, 'accueil');
        }
        
        //TODO changer la vue quand le models combat sera changer
        return $this->views->render($response, 'combat.html.twig',['combat' => $combat, 'personnage1'=> $personnageArray[0],'personnage2'=> $monstreArray[0]]);
    }
    
    /**
    * choix d'un ramdom selon l'agilite de chaque entite
    * le plus grand chiffre commence a attaque
    * @return Entite
    */
    public function choixAttaquant($personnage1, $personnage2){
        $val1 = 0;
        $val2 = 0;
        while($val1 === $val2){
            $val1 = mt_rand(0, $personnage1->pointAgi);
            $val2 = mt_rand(0, $personnage2->pointAgi);
        }
        if ($val1 > $val2){
            return $personnage1;
        }else{
            return $personnage2;
        }
    }
    
    
    /**
    * point d'attaque choisit aléatoirement entre 80% et 120% de l'attaquant
    * l'attaquant à 5% d'effectuer un coup critique (degat multiplie par 2)
    * la défence est augmenter de 1% du poids de la victime
    * la victime à 5% d'esquiver le coup
    */
    public function degat($attaquant, $victime) {
        $esquive = mt_rand(1, 100);
        if ($esquive <= 5) {
            return  $res = 0;
        } else {
            $critique = mt_rand(1, 100);
            if ($critique <= 5) {
                $critique = 2;
            } else {
                $critique = 1;
            }
            $att = mt_rand(0.8, 1.2);
            $defSup = (0.01*$victime->poids);
            $att_tt = ($attaquant->pointAtt*$att)*$critique;
            
            $res = $att_tt - $victime->pointDef+$defSup;
            var_dump("attaque = ".($attaquant->pointAtt*$att)*$critique);
            var_dump("defence = ".($victime->pointDef+$defSup));
            var_dump("res = ".$res);
            if ($res < 0){
                $res = 1;
            }
            return $res;
        }
    }
    
    //TODO Est-ce qu'on delete quand fini ?
    public function play(Request $request, Response $response, $args){
        $idCombat = Utils::sanitize($args['id']);
        $combat = Combat::find($idCombat);
        
        if($combat === null) {
            FlashMessage::flashError('Le combat n\'existe pas');
            Utils::redirect($response, 'accueil');
        }
        
        $personnage1 = Entite::find($combat->idPersonnage);
        $personnage2 = Entite::find($combat->idMonstre);
        $attaquant = $this->choixAttaquant($personnage1,$personnage2);
        
        if($this->isAlive($personnage1) || $this->isAlive($personnage2)){
            if ($attaquant === $personnage1){
                $degat = $this->degat($attaquant,$personnage2);
                $personnage2->pointVie = $personnage2->pointVie - $degat;
            }
            
            if($attaquant === $personnage2){
                $degat = $this->degat($attaquant,$personnage1);
                $personnage1->pointVie = $personnage1->pointVie - $degat;
            }
            $personnage2->save();
            $personnage1->save();
            
        }
        return $this->views->render($response, 'combat.html.twig',['combat' => $combat, 'personnage1'=> $personnage1,'personnage2'=> $personnage2]);        
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
    
    
    //    public function play(Response $response){
        //        while(isAlive($personnage) || isAlive($monstre)) {
            //            $attaquant = choixAttaquant();
            //            $victime = getVictime($attaquant);
            //            $degat($attaquant, $victime);
            //            // pause
            //        }
            //    }
            
        }
        