<?php


namespace Smash\controllers;

class Controller
{
    private $c = null;
    protected $views = null;

    public function __construct($container)
    {
        $this->c = $container;
        $this->views = $container["view"];
    }
}
