<?php
namespace MyApp\controls;

class Utils {
    
    /**
     * Permet de generer un nom de fichier et de le deplacer dans le bon dossier
     */
    public static function uploadFichier($directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(time().random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);
    
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        return $filename;
    }
    

}