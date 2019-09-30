<?php
use Smash\controllers\Auth;

return [
    new Twig_Function("est_connecte", Auth::class."::estConnecte"),
];