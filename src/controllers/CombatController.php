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
                FlashMessage::flashInfo('Vous devez terminé ce combat pour pouvoir en creer un nouveau');
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
        
        //TODO a changer lorsqu'il y  aura du 2v2
        if(count($personnages) !== 1 && count($monstres) !== 1) {
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
        
        foreach ($personnages as $personnage) {
            $participant = new Participant();
            $participant->pointVie = $personnage->pointVie;
            $participant->entite_id = $personnage->id;
            $participant->combat_id = $combat->id;
            $participant->save();
        }
        
        foreach ($monstres as $monstre) {
            $participant = new Participant();
            $participant->pointVie = $monstre->pointVie;
            $participant->entite_id = $monstre->id;
            $participant->combat_id = $combat->id;
            $participant->save();
        }
        
        setcookie("combat", json_encode($combat->id), time() + 3600*24*60, "/");
        
        return Utils::redirect($response, 'combat', ['id' => $combat->id]);
    }
    
    /**
    * choix d'un ramdom selon l'agilite de chaque entite
    * le plus grand chiffre commence a attaque
    * @return Entite
    */
    private function choixAttaquant($personnage1, $personnage2){
        $val1 = 0;
        $val2 = 0;
        while($val1 === $val2){
            $val1 = mt_rand(0, $personnage1->entite->pointAgi);
            $val2 = mt_rand(0, $personnage2->entite->pointAgi);
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
    private function degat($attaquant, $victime) {
        $esquive = mt_rand(1, 100);
        if ($esquive <= 5) {
            return 0;
        }
        $att = mt_rand(8, 12)/10;
        $critique = mt_rand(1, 100);
        if ($critique <= 5) {
            return round(($attaquant->entite->pointAtt*$att));
        }
        $reste = round($victime->entite->pointDef/20);
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
    private function terminerCombat($combat, $gagnant,  $perdant) {
        $combat->termine = true;
        setcookie("combat", "", -1, "/");
        
        $gagnant->gagner = 1;
        $gagnant->entite->combatGagne++;
        $gagnant->entite->totalDegatInflige += $gagnant->degatInflige;
        $gagnant->entite->totalDegatRecu = $gagnant->degatRecu;

        $perdant->entite->combatPerdu++;
        $perdant->entite->totalDegatInflige += $perdant->degatInflige;
        $perdant->entite->totalDegatRecu = $perdant->degatRecu;
        $gagnant->entite->save();
        $perdant->entite->save();
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
        
        if($combat->termine) {
            //si combat terminé, on affiche le résultat
            $vainqueur = $participant1->pointVie <= 0 ? $participant1->entite()->first() : $participant2->entite()->first();
            $personnages = [];
            array_push($personnages, [$participant1, $participant2]);
            return $this->views->render($response, 'affichageVainqueur.html.twig', ['personnages' => $personnages]);
        }
        $combat->nbTours++;
        //si Post, on update le combat
        //sinon on affiche la vue
        $messsage = "";
        if ($request->isPost()) {
            $choix = $this->choixAttaquant($participant1, $participant2);
            $attaquant = $choix['attaquant'];
            $victime = $choix['victime'];
            
            $degat = $this->degat($attaquant,$victime);
            // save statistique
            $attaquant->nbAttaqueInflige++;
            $attaquant->degatInflige += $degat;
            $victime->pointVie -= $degat;
            $messsage = $attaquant->entite->prenom . " " . $attaquant->entite->nom . " a infligé $degat dégats à " . $victime->entite->prenom . " " . $victime->entite->nom ;
            $victime->nbAttaqueRecu++;
            $victime->degatRecu += $degat;


            $personnages = [];
            
            if($victime->pointVie <= 0) {
                $vainqueur = $participant1->pointVie <= 0 ? $participant1->entite()->first() : $participant2->entite()->first();
                $perdant = $participant1->pointVie >= 0 ? $participant1->entite()->first() : $participant2->entite()->first();
                
                $this->terminerCombat($combat, $vainqueur, $perdant);

                $personnages = [];

                if ($vainqueur->id == $participant1->entite_id) {
                    $messsage .= "Le coup de grâce à été donné !";
                    array_push($personnages,$vainqueur);
                    array_push($personnages,$perdant);
                }else{
                    $messsage .= "Le coup de grâce à été donné !";
                    array_push($personnages,$vainqueur);
                    array_push($personnages,$perdant);
                }
    

                $nbr_degat_infliger_monstre = $attaquant->nbAttaqueRecu;
                $nbr_coup_porter_monstre = $perdant->nbAttaqueRecu;

                $nbr_degat_infliger_personnage = $attaquant->degatInflige;

                $nbr_coup_porter_personnage = $attaquant->nbAttaqueInflige;
                $nbr_coup_porter_monstre = $perdant->nbAttaqueInflige;
                $nbr_tour = $combat->nbTours;
                    

                //

                return $this->views->render($response, 'affichageVainqueur.html.twig', ['personnages' => $personnages,
                 'nbr_degat_infliger_monstre'=> $nbr_degat_infliger_monstre,
                 'nbr_degat_infliger_personnage'=> $nbr_degat_infliger_personnage,
                 'nbr_coup_porter_personnage'=> $nbr_coup_porter_personnage,
                 'nbr_coup_porter_monstre'=> $nbr_coup_porter_monstre,
                 'nbr_tour'=> $nbr_tour]);
            }       



            $attaquant->save();
            $victime->save();
            $combat->save();
        }
        return $this->views->render($response, 'combat.html.twig',['combat' => $combat, 'participant1'=> $participant1,'participant2'=> $participant2, 'message' => $messsage]);        
    }

}
