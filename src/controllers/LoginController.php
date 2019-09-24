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
        if($super_user == null){
            return $this->views->render($response, 'login.html.twig');
        }
        elseif ($pwd == $super_user->mdp && $login == $super_user->login){
            $_SESSION['login'] = $super_user->login;
            $_SESSION['mdp'] = $super_user->mdp;
            $admins = Admin::all();
            return $this->views->render($response, 'log.html.twig',['super_user'=> $super_user,'admins'=>$admins]);
        }
        else {
            return $this->views->render($response, 'login.html.twig');
        }
    }


}
