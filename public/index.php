<?php

use Slim\Factory\AppFactory;
use DI\Container as Container;
use Slim\Views\TwigMiddleware;

include_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();

include_once __DIR__ . '/../config/services.php';

$app = AppFactory::create();

$app->getContainer()->get('bootDb');

$app->addBodyParsingMiddleware();

$app->add(TwigMiddleware::createFromContainer($app));

include_once __DIR__ . '/../config/router.php';

$app->run();