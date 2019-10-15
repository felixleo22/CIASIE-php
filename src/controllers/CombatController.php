<?php

namespace Smash\controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use Smash\models\Entite;
use Smash\models\Combat;
use Smash\models\Participant;

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
        $created = $combat->save();
        if(!$created) {
            FlashMessage::flashError('Impossible de créer le combat');
            return Utils::redirect($response, 'accueil');
        }
        
        foreach ($personnageArray as $personnage) {
            $participant = new Participant();
            $participant->pointVie = $personnage->pointVie;
            $participant->entite_id = $personnage->id;
            $participant->combat_id = $combat->id;
            $participant->save();
        }

        foreach ($monstreArray as $monstre) {
            $participant = new Participant();
            $participant->pointVie = $monstre->pointVie;
            $participant->entite_id = $monstre->id;
            $participant->combat_id = $combat->id;
            $participant->save();
        }

        //TODO changer la vue quand le models combat sera changer
        return Utils::redirect($response, 'combat', ['id' => $combat->id]);
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
            return ['attaquant' => $personnage1, 'victime' => $personnage2];
        }else{
            return ['attaquant' => $personnage2, 'victime' => $personnage1];
        }
    }
    
    /**
    * La victime à 5% de chance d'esquiver le coup (return 0)
    * L'attaque est chosit entre 80% et 120% des point d'attaque de l'attaquant.
    * La defence est calculer en % avec des palier tout les 20 points de defense
    * Elle ne peut pas exeder 70%
    * Une attaque classique (return l'attaque l'attaquant entre 80 et 120% - le % de defence
    * L'attaquant peut effectuer un coup critique qui ignore la defense (return l'attaque de l'attaquant entre 80 et 120%)
    */
    public function degat($attaquant, $victime) : int{
        $esquive = mt_rand(1, 100);
        if ($esquive <= 5) {
            return 0;
        }
        $att = mt_rand(8, 12)/10;
        $critique = mt_rand(1, 100);
        if ($critique <= 5) {
            return round(($attaquant->pointAtt*$att));
        }
        $reste = round($victime->pointDef/20);
        if($reste > 7) {
            $reste = 7;
        }
        if($reste >= 0) {
            $reste = 10;
        }
        return round(($attaquant->pointAtt*$att)*($reste/10));
        
    }
    
    public function play(Request $request, Response $response, $args){
        
        $idCombat = Utils::sanitize($args['id']);
        $combat = Combat::find($idCombat);
        if($combat === null) {
            FlashMessage::flashError('Le combat n\'existe pas');
            Utils::redirect($response, 'accueil');
        }
        
        $entites = $combat->participants;
        $participant1 = $entites[0];
        $participant2 = $entites[1];
        
        if($combat->termine){
            //si combat terminé, on affiche le résultat
            $vainqueur = $participant1->pointVie <= 0 ? $participant1->entite()->first() : $participant2->entite()->first();

            return $this->views->render($response, 'affichageVainqueur.html.twig', ['entite' => $vainqueur]);
        }
        
        //si Post, on update le combat
        //sinon on affiche la vue
        $messsage = "";
        if ($request->isPost()) {
            $choix = $this->choixAttaquant($participant1, $participant2);
            $attaquant = $choix['attaquant'];
            $victime = $choix['victime'];
            
            $degat = $this->degat($attaquant,$victime);
            $victime->pointVie -= $degat;
            $messsage = "$degat points de dégats subits.";
            
            if($victime->pointVie <= 0) {
                $combat->termine = true;
                $messsage .= "Le coup de grâce à été donné !";
            }
            $combat->save();
         }
        return $this->views->render($response, 'combat.html.twig',['combat' => $combat, 'personnage1'=> $participant1->entite()->first(),'personnage2'=> $personnage2->entite()->first(), 'message' => $messsage]);        
    }
}
