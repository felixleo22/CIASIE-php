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

    //permet de verifier les infos de connexion et creer la connexion si elles sont correctes
    public static function connexion(string $login, string $mdp) : bool {
        if(static::estConnecte()) return true;

        $admin = Admin::where('login', '=', $login)->first();
        if($admin === null || !password_verify($mdp, $admin->mdp)) return false;

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

}