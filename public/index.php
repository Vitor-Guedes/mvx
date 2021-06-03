<?php

use Slim\Factory\AppFactory;
use DI\Container as Container;
use Slim\Views\TwigMiddleware;
use \Illuminate\Support\Facades\Facade;

include_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();

include_once __DIR__ . '/../config/services.php';

$app = AppFactory::create();

Facade::setFacadeApplication($app);

$app->addBodyParsingMiddleware();

$app->add(TwigMiddleware::createFromContainer($app));

include_once __DIR__ . '/../config/router.php';

$app->run();