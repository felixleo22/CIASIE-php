<?php

namespace Smash\controllers;

use Smash\models\Admin;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LoginController extends Controller
{
    public function index(Request $request, Response $response, $args){
        if(isset($args['username'])){
            $username = $args['username'];
        } else{
            $username = "";
        }
        return $this->views->render($response, 'login.html.twig', ['username' => $username]);
    }

    public function login(Request $request, Response $response){
        $login = ($_POST['login']);
        $pwd = ($_POST['password']);
        if(Auth::connexion($login,$pwd) == false){
            return $this->views->render($response, 'login.html.twig');
        }
        elseif (Auth::connexion($login,$pwd) == true && Auth::getAdmin()->super == 0){
            return $response->withRedirect('/entite/liste');
        }
        elseif(Auth::connexion($login,$pwd) == true && Auth::getAdmin()->super == 1){
            return $response->withRedirect('/admin/liste');
        }
        else {
            return $this->views->render($response, 'login.html.twig');
        }
    }

    public function deconnect(Request $request, Response $response){
        session_abort();
        return $response->withRedirect('/');

    }


}
