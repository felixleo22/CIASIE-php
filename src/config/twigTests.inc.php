<?php
use Smash\controllers\Auth;
use Smash\controllers\FlashMessage;

/**
 * Fonction utilisé dans les templates twig * 
 */
return [
    new Twig_Test('flashed', FlashMessage::class.'::has'),
];