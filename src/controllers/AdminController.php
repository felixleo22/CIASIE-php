<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 24/09/2019
 * Time: 17:36
 */

namespace MyApp\controllers;

use MyApp\models\Admin;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


class AdminController extends Controller
{
    public function listeAdmin(Request $request, Response $response)
    {
        if ($_SESSION['current_user'] == null) {
            return $response->withRedirect('/');
        } else{
            $admins = Admin::all();
        return $this->views->render($response, 'log.html.twig', ['admins' => $admins, 'session' => $_SESSION['current_user']]);
    }
    }

    public function formulaireEditAdmin(Request $request, Response $response, $args){
        return $this->views->render($response, 'ajoutEntite.html.twig');
    }


    public function suppressionAdmin(Request $request, Response $response){
        Admin::where('id',intval($_POST['id']))->delete();
//        if($admin){
//            Admin::destroy($admin);
//        }
        return $response->withRedirect('/liste-admin');
    }

}