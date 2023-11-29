<?php

require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Application;

$app = new Application(dirname(__DIR__));

// echo var_dump($app->router);

$app->router->get('/', 'home');

$app->router->get('/contact', 'contact');

$app->run();