<?php

namespace MyApp\controllers;

use MyApp\models\Admin;
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
        $super_user = Admin::where('login','=',$login)->first();
        $_SESSION['current_user'] = $super_user;
        if($super_user == null){
            return $this->views->render($response, 'login.html.twig');
        }
        elseif ($pwd == $super_user->mdp && $login == $super_user->login && $super_user->super == 0){
            return $response->withRedirect('/liste-entite');
        }
        elseif($pwd == $super_user->mdp && $login == $super_user->login && $super_user->super == 1){
            $admins = Admin::all();
            return $this->views->render($response, 'log.html.twig',['super_user'=> $super_user,'admins'=>$admins,'session'=>$_SESSION['current_user']]);
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
