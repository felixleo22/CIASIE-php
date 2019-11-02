<?php

namespace Smash\controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

use Smash\models\Entite;
use Smash\models\Combat;
use Smash\models\Participant;

class CombatController extends Controller {
    
    /**
    * affiche la liste des combats finis.
    */
    public function affichageListeCombat(Request $request, Response $response) {
        $listeCombat = Combat::where('termine', 1)->get();
        $combats = [];
        foreach ($listeCombat as $combat){
            $id = $combat->id;
            $participants = $combat->participants;
            $personnage = $participants[0];
            $monstre = $participants[1];
            $combats[] = array(
                'id' => $id,
                'personnage' => $personnage,
                'monstre' => $monstre
            );
        }
        return $this->views->render($response, 'affichageCombats.html.twig', ['combats' => $combats]);
    }
    
    public function creerCombat(Request $request, Response $response, $args) {
        $combatCookie = isset($_COOKIE["combat"]) ? Utils::sanitize(json_decode($_COOKIE["combat"])) : null;
        if($combatCookie) {
            $combat = Combat::find($combatCookie);
            if($combat && !$combat->termine){
                FlashMessage::flashInfo('Vous devez terminer le combat en cours pour pouvoir en créer un nouveau');
                return Utils::redirect($response, 'combat', ['id' => $combat->id]);
            }
        }
        
        $data = Utils::getFilteredPost($request, 'ids');
        $personnages = [];
        $monstres = [];
        foreach ($data as $idEntite) {
            $entite = Entite::find($idEntite);
            if($entite === null) {
                FlashMessage::flashError('Une ou plusieurs des entités séléctionnées n\'existent pas');
                return Utils::redirect($response, 'accueil');
            }
            
            if($entite->type === "monstre") {
                array_push($monstres, $entite);
            }else{
                array_push($personnages, $entite);
            }
        }
        
        //recuperation du mode du combat
        $combatMode = Utils::getFilteredPost($request, 'combatMode');
        if($combatMode !== '1v1' && $combatMode !== '3v3') {
            FlashMessage::flashError($combatMode.' n\'est pas un mode de combat valide');
            return Utils::redirect($response, 'accueil');
        }
        
        if($combatMode === '1v1' && count($personnages) !== 1 && count($monstres) !== 1) {
            FlashMessage::flashError('Vous devez choisir un personnage et un monstre en mode 1 VS 1');
            return Utils::redirect($response, 'accueil');
        }
        
        if($combatMode === '3v3' && count($personnages) !== 3 && count($monstres) !== 3) {
            FlashMessage::flashError('Vous devez choisir trois personnages et trois monstres en mode 3 VS 3');
            return Utils::redirect($response, 'accueil');
        }
        
        //TODO verifier les types
        $combat = new Combat();
        $combat->mode = $combatMode;
        $created = $combat->save();
        if(!$created) {
            FlashMessage::flashError('Impossible de créer le combat');
            return Utils::redirect($response, 'accueil');
        }
        
        $participantsPerso = [];
        foreach ($personnages as $personnage) {
            $participant = new Participant();
            $participant->pointVie = $personnage->pointVie;
            $participant->entite_id = $personnage->id;
            $participant->combat_id = $combat->id;
            $participant->save();
            
            $participantsPerso[] = $participant;
        }
        
        $participantsMonstre = [];
        foreach ($monstres as $monstre) {
            $participant = new Participant();
            $participant->pointVie = $monstre->pointVie;
            $participant->entite_id = $monstre->id;
            $participant->combat_id = $combat->id;
            $participant->save();
            
            $participantsMonstre[] = $participant;
        }
        
        $this->choixAttaquant($combat, $participantsPerso, $participantsMonstre);
        
        setcookie("combat", json_encode($combat->id), time() + 3600*24*60, "/");
        
        return Utils::redirect($response, 'combat', ['id' => $combat->id]);
    }
    
    /**
    * choix d'un ramdom selon l'agilite de chaque entite
    * le plus grand chiffre commence a attaque
    * @return Entite
    */
    //TODO modifier pour que cela fonctionne en 3v3
    private function choixAttaquant($combat, $personnages, $monstres){
        $personnages = array_filter($personnages, function($elem) {
            return $elem->pointVie > 0;
        });
        
        $monstres = $victimes = array_filter($monstres, function($elem) {
            return $elem->pointVie > 0;
        });
        
        $max = null;
        $maxCoeff = -1;
        
        foreach ($personnages as $p) {
            $coef = $this->coeffAttaque($p);
            if($coef > $maxCoeff) {
                $max = $p;
                $maxCoeff = $coef;
            }
        }
        
        foreach ($monstres as $m) {
            $coef = $this->coeffAttaque($m);
            if($coef > $maxCoeff) {
                $max = $m;
                $maxCoeff = $coef;
            }
        }
        
        $attaquant = $max;
        
        $victimes = [];
        if($attaquant->entite->type === 'personnage') {
            $victimes = $monstres;
        }else{
            $victimes = $personnages;
        }

        $index = rand(0, count($victimes) - 1);
        $victime = $victimes[$index];
        
        $combat->prochainAttaquant = $attaquant->id;
        $combat->prochainVictime = $victime->id;
        $combat->save();
        
        //FIXME retourne parfois null en 3v3
        return $attaquant;
    }
    
    private function coeffAttaque($participant) {
        $agi = rand(0, $participant->entite->pointAgi);
        return $agi / $participant->entite->pointAgi;
    }
    
    /**
    * La victime à 5% de chance d'esquiver le coup (return 0)
    * L'attaque est chosit entre 80% et 120% des point d'attaque de l'attaquant.
    * La defence est calculer en % avec des palier tout les 20 points de defense
    * Elle ne peut pas exeder 70%
    * Une attaque classique (return l'attaque l'attaquant entre 80 et 120% - le % de defence
    * L'attaquant peut effectuer un coup critique qui ignore la defense (return l'attaque de l'attaquant entre 80 et 120%)
    * Si la victime est en defensif, ca defence est multiplie par 1.25
    */
    private function degat($attaquant, $victime) {
        $defense = $victime->entite->pointDef;
        if($victime->defensif) {
            $defense *= 1.25;
        }
        
        $esquive = mt_rand(1, 100);
        if ($esquive <= 5) {
            return 0;
        }
        $att = mt_rand(8, 12)/10;
        $critique = mt_rand(1, 100);
        if ($critique <= 5) {
            return round(($attaquant->entite->pointAtt*$att));
        }
        $reste = round($defense/20);
        if($reste > 7) {
            $reste = 7;
        }
        if($reste >= 0) {
            $reste = 10;
        }
        return round(($attaquant->entite->pointAtt*$att)*($reste/10));
    }
    
    /**
    * Methode pour cloturer un combat
    */
    //TODO modifier pour que cela fonctionne en 3v3
    private function terminerCombat($combat, $gagnants, $perdants) {
        $combat->termine = true;
        setcookie("combat", "", -1, "/");
        
        foreach ($gagnants as $gagnant) {
            $gagnant->gagner = 1;
            $gagnant->entite->combatGagne++;
            $gagnant->entite->totalDegatInflige += $gagnant->degatInflige;
            $gagnant->entite->totalDegatRecu += $gagnant->degatRecu;
            $gagnant->entite->save();     
        }
        
        foreach ($perdants as $perdant) {
            $perdant->entite->combatPerdu++;
            $perdant->entite->totalDegatInflige += $perdant->degatInflige;
            $perdant->entite->totalDegatRecu += $perdant->degatRecu;
            $perdant->entite->save();
        }
        
        $combat->save();
    }
    
    public function play(Request $request, Response $response, $args){  
        $idCombat = Utils::sanitize($args['id']);
        $combat = Combat::find($idCombat);
        if($combat === null) {
            //TODO faire qq chose si le combat n'existe pas
        }
        
        if($combat->termine) {
            return $response->withJson(['showResult' => true], 201);
        }
        
        $combat->nbTours++;
        
        $messsage = "";
        
        $entites = $combat->participants;
        
        //recuperation de l'attaquant et de la victime et trie par type
        $personnages = [];
        $monstres = [];
        
        $attaquant = null;
        $victime = null;
        foreach ($entites as $participant) {
            //trie par type
            if($participant->entite->type === 'personnage') {
                $personnages[] = $participant;
            }
            
            if($participant->entite->type === 'monstre') {
                $monstres[] = $participant;
            }
            
            //verfie si c'est l'attaquant ou la victime
            if($combat->prochainAttaquant === $participant->id) {
                $attaquant = $participant;
            }
            if($combat->prochainVictime === $participant->id) {
                $victime = $participant;
            }
        }
        
        //le message sur le tour en cours
        $messsage = "";
        
        //execution du tour
        $actionOfPersonnage = Utils::getFilteredPost($request, 'chosenAction');
        
        if($attaquant->entite->type === 'personnage' && $actionOfPersonnage === 'defendre'){
            //si le perso joue et qu'il défend
            $attaquant->defensif = true;
            $messsage .= 'Vous avez defendu ! (augmentation de la défense de 25% jusqu\'au prochain tour ou coup subit).';
        }else{
            //sinon un monstre joue ou que le perso attaque
            
            // calcul des degats
            $degat = $this->degat($attaquant,$victime);
            
            // save statistique
            $attaquant->nbAttaqueInflige++;
            $attaquant->degatInflige += $degat;
            $victime->pointVie -= $degat;
            $messsage .= $attaquant->entite->prenom . " " . $attaquant->entite->nom . " a infligé $degat dégats à " . $victime->entite->prenom . " " . $victime->entite->nom . '.' ;
            $victime->nbAttaqueRecu++;
            $victime->degatRecu += $degat;  
            
            
            // remise à zéro de la défense
            $attaquant->defensif = false;
            $victime->defensif = false;            
        }     
        
        //choix du prochain ou fin du combat            
        $typeOfNext = null;
        
        if($this->isTerminated($victime, $personnages, $monstres)) {
            $this->terminerCombat($combat, $personnages, $monstres);
            $messsage .= " Le coup de grâce à été donné !";
            $typeOfNext = 'ended';
        }else{  
            //choix de l attaquant et de la victime au prochain tours;
            $prochain = $this->choixAttaquant($combat, $personnages, $monstres);
            $messsage .= ' C\'est au tour de '.$prochain->entite->prenom." de jouer.";
            $typeOfNext = $prochain->entite->type;
        }   
        
        $attaquant->save();
        $victime->save();

        $combat->save();
        
        $data = ['attaquant' => $attaquant, 'victime' => $victime, 'typeOfNext' => $typeOfNext, 'message' => $messsage];
        return $response->withJson($data, 201); 
    }
    
    private function isTerminated($victime, $personnages, $monstres) : bool{
        $participants = $victime->entite->type === 'monstre' ? $monstres : $personnages;
        
        foreach ($participants as $p) {
            if($p->pointVie > 0) {
                return false;
            }
        }
        return true;
    }
    
    /**
    * Permet de récupérer des infos nécessaires au démarrage du combat
    */
    public function commencerCombat(Request $request, Response $response, $args) {
        $idCombat = Utils::sanitize($args['id']);
        $combat = Combat::find($idCombat);
        if($combat === null) {
            //TODO faire qq chose si le combat n'existe pas
        }
        
        if($combat->termine) {
            return $response->withJson(['showResult' => true], 201);
        }
        
        $combat->nbTours++;
        
        $attaquant = Participant::find($combat->prochainAttaquant);
        
        $messsage = $attaquant->entite->prenom . ' joue en premier !';
        
        $data = ['typeOfNext' => $attaquant->entite->type, 'message' => $messsage];
        return $response->withJson($data, 201); 
    }
    
    /**
    *  Affiche la vue du combat
    */
    public function afficherCombat(Request $request, Response $response, $args) {
        //récupération du combat
        $idCombat = Utils::sanitize($args['id']);
        $combat = Combat::find($idCombat);
        if($combat === null) {
            FlashMessage::flashError('Le combat n\'existe pas');
            Utils::redirect($response, 'accueil');
        }
        
        $entites = $combat->participants;
        
        $personnages = [];
        $monstres = [];
        
        foreach ($entites as $participant) {
            //trie par type
            if($participant->entite->type === 'personnage') {
                $personnages[] = $participant;
            }
            
            if($participant->entite->type === 'monstre') {
                $monstres[] = $participant;
            }
        }
        
        if($combat->termine) {
            //si combat terminé, on affiche le résultat
            //TODO affichage 3v3 du resultat
            return $this->views->render($response, 'affichageVainqueur.html.twig', ['combat' => $combat]);
        }
        
        return $this->views->render($response, 'combat.html.twig',['combat' => $combat, 'personnages'=> $personnages,'monstres'=> $monstres]);          
    }
    
}
