<?php

define('BASEPATH', dirname(__DIR__));

use DI\Container;
use Slim\Views\Twig;
use Slim\Factory\AppFactory;
use App\Factory\DatabaseManager;

AppFactory::setContainer($container);

$container->set('settings', function () {
    $settings = [];

    if (getenv('DATABASE_URL')) {
        $settings['db'] = [
            'driver'   => 'pgsql',
            'user' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'host' =>  getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'dbname' => getenv('DB_NAME')
        ];
    } else {
        /** for dev */
        $settings['db'] = [
            'driver' => 'mysql',
            'host' => '172.10.10.7',
            'database' => 'mvxs',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => ''
        ];
    }
    
    return $settings;
});

$container->set('view', function() {
    return Twig::create(BASEPATH . '/views', ['cache' => false]);
});

$container->set('db', function (Container $container) {
    $factory = new DatabaseManager($container);
    return new $factory->capsule;
});

$container->get('db');