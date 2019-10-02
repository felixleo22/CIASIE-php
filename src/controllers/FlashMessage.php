<?php
namespace Smash\controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class FlashMessage {
    
    private static $flashedData;
    private static $validated = false;
    
    /**
    *    Fonction d'initialisation interne
    */
    private static function init()
    {
        if (! isset($_SESSION["flash"]))
        {
            $_SESSION['flash'] = [];
        }
        if (self::$flashedData === null)
        self::$flashedData = [];
    }
    
    /**
    * Permet de savoir si une valeur est associée à la clef $name dans les données
    */
    public static function has($name) {
        self::init();
        if (is_array($name))
        {
            $array = $_SESSION["flash"];
            foreach($name as $key)
            {
                if (!isset($array[$key]))
                return false;
                
                $array = $array[$key];
            }
            return true;
        }
        else
        return isset($_SESSION["flash"][$name]);
    }
    
    /**
    * Permet de retourner la valeur associée à la clef $name, ou null si non présente
    */
    public static function get($name) {
        self::init();
        if (!self::has($name))
        return null;
        
        if (is_array($name))
        {
            $value = $_SESSION["flash"];
            foreach($name as $key)
            $value = $value[$key];
            
            return $value;
        }
        else
        return $_SESSION["flash"][$name];
    }
    
    /**
    * Permet de sauvegarder pour la prochaine éxécution la valeur $value avec la clef $key.
    * Ecrase la valeur précédente si la clef est déjà présente
    */
    private static function flash(string $key, $value)
    {
        self::init();
        self::$flashedData[$key] = $value;
    }

    public static function flashError($value) {
        self::flash('error', $value);
    } 

    public static function flashSuccess($value) {
        self::flash('success', $value);
    } 
    
    /**
    * Supprime toutes les données pour l'éxécution suivante
    */
    public static function clear()
    {
        self::init();
        $_SESSION['flash'] = [];
        self::$flashedData = [];
    }

    public static function middleware() {
        self::init();
        if (!self::$validated)
        {
            $_SESSION['flash'] = self::$flashedData;
            self::$flashedData = [];
            self::$validated = true;
        }
    }
}