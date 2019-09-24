<?php
global $BASE_URL;

$config=array();

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config["db"] = [
    'driver' => "mysql",
    'host' => "localhost",
    'database' => "Smash2",
    'username' => "",
    'password' => "",
    'charset' => "utf8",
    'collation' => "utf8_unicode_ci",
    'prefix' => ''
];
