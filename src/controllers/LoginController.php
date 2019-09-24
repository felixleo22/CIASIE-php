<?php

namespace MyApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LoginController extends Controller
{
    public function index(Request $request, Response $response, $args){
        return $this->views->render($response, 'login.html.twig');
    }
}
