<?php

namespace MyApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LoginController
{
    public function index(Request $request, Response $response, $args){
        if (isset($args['defaultUsername'])){
            $defaultUserName = $args['defaultUsername'];
        } else{
            $defaultUserName = "";
        }
        return $response->getBody()->write("Hello ${defaultUserName} !");
    }
}
