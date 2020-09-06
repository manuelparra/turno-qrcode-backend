<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';

$auxcontainer = new \DI\Container();

AppFactory::setContainer($auxcontainer);

$app = AppFactory::create();

$container = $app->getContainer();

// ...

$app->add(\App\Middleware\CorsMiddleware::class); // <--- here

// The RoutingMiddleware should be added after our CORS middleware
// so routing is performed first
$app->addRoutingMiddleware();

// ...

require __DIR__ . '/Routes.php';
require __DIR__ . "/Configs.php";
require __DIR__ . "/Dependencies.php";

$app->run();
?>
