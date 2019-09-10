<?php


namespace MyApp\controllers;


class LoginController
{
    public function hello($request, $response, $args){
        if (isset($args['name'])){
            $name = $args['name'];
            $response->getBody()->write("Hello, ${name}!");
        }
        else{
            $response->getBody()->write("Hello world!");
        }

        return $response;
    }
}
