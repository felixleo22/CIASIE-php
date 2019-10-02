<?php
use Smash\controllers\Auth;
use Smash\controllers\FlashMessage;

return [
    new Twig_Test('flashed', FlashMessage::class.'::has'),
];