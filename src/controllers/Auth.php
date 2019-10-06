<?php
namespace Smash\controllers;

use Smash\models\Admin;

//Class de methode static pour faciliter la gestion de la connexion à un compte admin
class Auth {

    //permet de verifier si l utilisateur est connecte
    public static function estConnecte() : bool {
        return isset($_SESSION['user']);
    }

    //retourne l objet admin correspondant à celui connecter (retourne null si pas connecter)
    public static function getAdmin() : Admin {
        if(!static::estConnecte()) return null;

        return Admin::find($_SESSION['user']['id']);
    }

    //retourne le login de l'admin
    public static function getAdminLogin() : string {
        if(!static::estConnecte()) return "";
        return $_SESSION['user']['login'];
    }

    public static function getAdminId() : int {
        if (!static::estConnecte()) {
            return -1;
        } else {
            return $_SESSION['user']['id'];
        }
    }
    
    //permet de verifier les infos de connexion et creer la connexion si elles sont correctes
    public static function connexion(string $login, string $mdp) : bool {
        if(static::estConnecte()) return true;

        $admin = self::verifierMdp($login, $mdp);
        if($admin === null) return false;

        $_SESSION['user']['id'] = $admin->id;
        $_SESSION['user']['login'] = $admin->login;
        return true;
    }

    //permet la deconnexion de l'utilisateur connecter
    public static function deconnexion() {
       unset($_SESSION['user']); 
    }

    //permet de creer un compte admin (si pas marcher retourne null)
    public static function creerAdmin(string $login, $mdp,$super = false) {
        $admin = Admin::create(['login' => $login, 'mdp' => password_hash($mdp, PASSWORD_DEFAULT),'super'=>$super]);
        return $admin;
    }

    public static function loginDisponible(string $login) : bool {
        if (!$login)
            return false;
        else {
            return !Admin::where("login", "=", $login)->count() > 0; 
        }
    }

    private static function verifierMdp(string $login, string $mdp) {
        $admin = Admin::where("login", "=", $login)->first();
        return ($admin != null && password_verify($mdp, $admin->mdp)) ? $admin : null;
    }

    public static function modifierMdp(string $ancienMdp, string $nouveauMdp) : bool {
        
        $admin = self::verifierMdp(self::getAdminLogin(), $ancienMdp);
        if (!$admin) { return false; }
        
        $admin->mdp = password_hash($nouveauMdp, PASSWORD_DEFAULT);
            return $admin->save();
        }
}