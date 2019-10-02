<?php
namespace Smash\middlewares;

use Smash\controllers\Auth;
use Smash\controllers\Utils;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthMiddleware {

    public function __invoke(Request $request, Response $response, $next) {
        if(!Auth::estConnecte()) {
            return Utils::redirect($response, 'formConnexion');
        }
        return $next($request, $response);
    }
}
