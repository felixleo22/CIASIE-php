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
        return $data === null ? null : self::sanitize($data);
    }
    
    /**
    * Permet de sanitize une string (vis-à-vis de l'affichage HTML seulement)
    */
    public static function sanitize(string $unsafe) : string{
        return strip_tags($unsafe);
    }
}
