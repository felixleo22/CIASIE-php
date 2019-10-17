<?php

namespace Smash\controllers;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\UploadedFile;

class Utils {

    private static $uploadDirectory = '/uploaded';
    private static $acceptedFiles = ['gif', 'jpg', 'jpeg', 'png'];

    /**
    * Permet de generer un nom de fichier et de le deplacer dans le bon dossier
    */
    public static function uploadFichier(UploadedFile  $uploadedFile) {
        $directory = '../public'.self::$uploadDirectory;
        if (!is_dir($directory)){ 
            mkdir($directory); 
        }
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(time().random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);
        
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        return $filename;
    }
    
    public static function getUploadedPhoto($file, $default) : string {
        $img = self::$uploadDirectory.'/'.$file;
        
        if(is_file('../public'.$img)) {
            return $img;
        }
        
        return '/img/'.$default;
    }

    public static function isAcceptedFile(UploadedFile  $uploadedFile) : bool {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
         return in_array($extension, self::$acceptedFiles);
    }
    
    public static function redirect(ResponseInterface $response, $route, $args = [])
    {
        global $app;
        return $response->withRedirect($app->getContainer()->get('router')->pathFor($route, $args));
    }
    
    /**
    * Permet de récupérer une variable POST et de la filtrer
    * Retourne null si $key n'est pas présentes dans la requête
    */
    public static function getFilteredPost(ServerRequestInterface $request, string $key) {
        $data = $request->getParsedBodyParam($key, null);
        
        if($data === null) return null;
        if(is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
            return $data;
        }

        return self::sanitize($data);
    }

    public static function verifIfNumber($data) : bool {
        return filter_var($data, FILTER_VALIDATE_INT);
    }

    /**
    * Permet de sanitize une string (vis-à-vis de l'affichage HTML seulement)
    */
    public static function sanitize(string $unsafe) : string{
        return strip_tags($unsafe);
    }

    /**
     * Filtre une liste d'entité selon le type passé en paramètre
     * @param array $tab - La table que l'on souhaite filtrer
     * @param string $type - Le type selon lequel on filtre
     * @return array $res - Une nouvelle table filtrée
     */
    public static function filter($tab, $type){
        $res = [];
        foreach ($tab as $entite){
            if ($entite->type == $type ){
                $res[] = $entite;
            }
        }
        return $res;
    }
}
