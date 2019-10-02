<?php
use Smash\controllers\Auth;
use Smash\controllers\FlashMessage;

return [
    new Twig_Function("est_connecte", Auth::class."::estConnecte"),
    new Twig_Function("get_message", FlashMessage::class."::get"),
    new Twig_Function("get_admin_login", Auth::class."::getAdminLogin")
];
