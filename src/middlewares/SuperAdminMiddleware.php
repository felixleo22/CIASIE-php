<?php
namespace Smash\middlewares;

use Smash\controllers\FlashMessage;
use Smash\controllers\Auth;
use Smash\controllers\Utils;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SuperAdminMiddleware {

    public function __invoke(Request $request, Response $response, $next) {
        if(!Auth::estSuperAdmin()){
            //TODO, à la fin du projet, changer le message d'erreur
            FlashMessage::flashError('Vous ne pouvez pas les droits pour modifer, creer, supprimer (il faut etre super admin)');
            return Utils::redirect($response, 'listeAdmins'); 
        }
        return $next($request, $response);
    }
}