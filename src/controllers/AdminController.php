<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 24/09/2019
 * Time: 17:36
 */

namespace Smash\controllers;

use Smash\models\Admin;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class AdminController extends Controller {

    public function formulaireEditAdmin(Request $request, Response $response, $args){
        $admin = Admin::find($request->getAttribute('id'));
        if($admin === NULL) {
            FlashMessage::flashError('Impossible de modifier cet utilisateur');
            return Utils::redirect($response, 'listeAdmins');   
        }
        return $this->views->render($response, 'editAdmin.html.twig',['admin'=>$admin]);
    }

    /**
     * affiche le formulaire de creation d'un admin via un fichier twig
     */
    public function formulaireCreation(Request $request, Response $response, $args){
        return $this->views->render($response, 'formAdmin.html.twig');
    }

    /**
     * creation de d'un admin
     * verification des logins
     */
    public function creerAdmin(Request $request, Response $response, $args) {
        $login = Utils::getFilteredPost($request, 'login');
        if(!Auth::loginDisponible($login)){
            FlashMessage::flashError('Login deja utilisé');
            return Utils::redirect($response, 'formCreerAdmin', ['id' => $admin->id]);   
        }
        $password = Utils::getFilteredPost($request, 'mdp');
        $passwordConf = Utils::getFilteredPost($request, 'mdp_conf');
        if ($password !== $passwordConf) {
            FlashMessage::flashError('Les mots de passe ne correspondent pas');
            return Utils::redirect($response, 'formCreerAdmin', ['id' => $admin->id]);
        }
        $admin = Auth::creerAdmin($login, $password);
        FlashMessage::flashSuccess('L\'admin a été créé !');
        return Utils::redirect($response, 'listeAdmins');
    }

    /**
     * selectionne tout les admins de la bdd et les affichent
     */
    public function listeAdmin(Request $request, Response $response, $args) {
        if(Auth::estSuperAdmin()){
            $listeAdmin = Admin::all();
        return $this->views->render($response, 'affichageAdminSuper.html.twig', ['admins' => $listeAdmin]);
        } else {
            $listeAdmin = Admin::all();
            return $this->views->render($response, 'affichageAdmin.html.twig', ['admins' => $listeAdmin]);
        }
    }

    /**
     * fenetre d'edition admin
     */
    public function afficherAdmin(Request $request, Response $response, $args) {
        $admin = Admin::find($request->getAttribute('id'));
        return $this->views->render($response, 'formAdmin.html.twig',['admins'=>$admin]);
    }

    /**
     * enrigistrement dans la base de donner des modifications de l'admins
     * verification des logins
     */
    public function modifierAdmin(Request $request, Response $response, $args) {
        $id = Utils::sanitize($args['id']);
        if($id === null) return Utils::redirect($request, 'listeAdmins');
        $admin = Admin::find($id);
         if($admin == null) {
            FlashMessage::flashError('Cet admin n\'existe pas !');
            return Utils::redirect($response, 'listeAdmins');
        }
        $admin->login = Utils::getFilteredPost($request, "login");
        if(!Auth::loginDisponible($admin->login)){
            FlashMessage::flashError('login deja utilisé');
            return Utils::redirect($response, 'formModifAdmin',['id' => $admin->id]);   
        }
        $admin->save();

        FlashMessage::flashSuccess($admin->login.' a été modifié !');
        return Utils::redirect($response, 'listeAdmins');
    }

    /**
     * supprime un admin dans la base de donnee
     * verification si suppresion du super admin
     */
    public function suppressionAdmin(Request $request, Response $response, $args){
        $id = Utils::sanitize($args['id']);
        $admin = Admin::find($id);
        if($admin == null) {
            FlashMessage::flashError('Cet admin n\'existe pas !');
            return Utils::redirect($response, 'listeAdmins');
        }
        if($admin->super === 1) {
            //TODO a la fin du projet, changer le message d'erreur
            FlashMessage::flashError('Impossible de supprimer le super admin');
            return Utils::redirect($response, 'listeAdmins');
        }
        $admin->delete();
        FlashMessage::flashSuccess($admin->login.' a été supprimé !');
        return Utils::redirect($response, 'listeAdmins');
    }

    public function afficherFomulaireConnexion(Request $request, Response $response, $args) {
        return $this->views->render($response, 'login.html.twig');
    }

    /**
     * execute la connexion
     */
    public function connecter(Request $request, Response $response, $args){
        $login = Utils::getFilteredPost($request,'login');
        $pwd = Utils::getFilteredPost($request, 'password');
        if(!Auth::connexion($login,$pwd)){
            FlashMessage::flashError('Login ou mot de passe incorrecte');
            return Utils::redirect($response, 'formConnexion');
        }
        
        FlashMessage::flashSuccess('Vous êtes connecté en tant que '.$login);
        return Utils::redirect($response, 'accueil');
    }

    public function deconnecter(Request $request, Response $response){
        Auth::deconnexion(); 
        return Utils::redirect($response, 'accueil');
    }


    public function afficherModiferMdp(Request $request, Response $response) {
        return $this->views->render($response, 'editMdpAdmin.html.twig');
    }

    public function modifierMdp(Request $request, Response $response) {
        $mdp = Utils::getFilteredPost($request, 'mdp');
        $mdpNew = Utils::getFilteredPost($request, "mdp_new");
        $mdpConf = Utils::getFilteredPost($request, "mdp_conf");

        if ($mdpNew == null || $mdp === null || $mdpConf == null) {
            FlashMessage::flashError("Des données sont manquantes");
            return Utils::redirect($response, "formModifMdpAdmin");
        }

        if ($mdpNew !== $mdpConf) {
            FlashMessage::flashError("Le mot de passe et sa confirmation ne correspondent pas");
            return Utils::redirect($response, "formModifMdpAdmin");
        }

        if (!Auth::modifierMdp($mdp, $mdpNew)) {
            FlashMessage::flashError("L'ancien mot de passe ne correspond pas");
            return Utils::redirect($response, "formModifMdpAdmin");
        }

        FlashMessage::flashSuccess("Le mot de passe a été changé");
        return Utils::redirect($response, "formModifMdpAdmin");
    }
}
