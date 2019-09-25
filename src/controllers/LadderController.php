<?php


namespace Smash\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

class LadderController extends Controller
{
    public function index(Request $request, Response $response, array $args){
        $this->views->render($response, 'ladder.html.twig', $args);
    }
}
