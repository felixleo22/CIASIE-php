<?php
namespace MyApp\controllers;

class Controleur {
    protected $view;

    function __construct(Twig $view) {
        $this->view = view;
    }

}