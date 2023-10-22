<?php

use JonathanRayln\UdemyClone\Application;

define('ROOTPATH', dirname(__DIR__));
define('APP_PATH', ROOTPATH . '/App/');
define('TEMPLATE_PATH', ROOTPATH . '/resources/views/');

include_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

foreach (glob(APP_PATH . 'helpers/*.php') as $resource) {
    include_once $resource;
}

$config = [];
foreach (glob(ROOTPATH . '/config/*.php') as $file) {
    $config[pathinfo($file)['filename']] = require_once $file;
}

$app = new Application($config, dirname(__DIR__));
$app->run();