<?php
/**
 * Created by PhpStorm.
 * User: simon
 * Date: 24/09/2019
 * Time: 17:36
 */

namespace Smash\controllers;

use Smash\models\Admin;
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
        $admin = Admin::find($request->getAttribute('id'));
        return $this->views->render($response, 'editAdmin.html.twig',['admin'=>$admin]);
    }


    public function suppressionAdmin(Request $request, Response $response){
        Admin::where('id',intval($_POST['id']))->delete();
        return $response->withRedirect('/admin/liste');
    }

    public function verifAdmin(Request $request, Response $response, $args){

        $perso = [];
        $perso['login'] = $request->getParsedBodyParam('login');
        $perso['mdp'] = $request->getParsedBodyParam('mdp');
        $perso['super'] = $request->getParsedBodyParam('super');

        $admin = Admin::find(intval($_POST['id']));
        $admin->login = $perso['login'];
        $admin->mdp = $perso['mdp'];
        $admin->super = $perso['super'];
        $admin->save();

        return $response->withRedirect('/admin/liste');
    }

}
