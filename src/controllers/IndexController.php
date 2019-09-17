<?php


namespace MyApp\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class IndexController
{
    public function index(Request $request, Response $response){
        $response->getBody()->write("Hello world!");
        return $response;
    }

}
