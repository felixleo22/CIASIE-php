<?php
use Smash\controllers\Auth;
use Smash\controllers\FlashMessage;
use Smash\controllers\Utils;

/**
 * Fonctions utilisées dans les templates twig
 */
return [
    new \Twig\TwigFunction("est_connecte", Auth::class."::estConnecte"),
    new \Twig\TwigFunction("get_message", FlashMessage::class."::get"),
    new \Twig\TwigFunction("get_photo", Utils::class.'::getUploadedPhoto'),
    new \Twig\TwigFunction("get_admin_login", Auth::class."::getAdminLogin")
];
