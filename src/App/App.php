<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

$auxcontainer = new \DI\Container();

AppFactory::setContainer($auxcontainer);

$app = AppFactory::create();

$container = $app->getContainer();

require __DIR__ . '/Routes.php';
require __DIR__ . "/Configs.php";
require __DIR__ . "/Dependencies.php";

$app->run();
?>
