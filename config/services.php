<?php

define('BASEPATH', dirname(__DIR__));

use DI\Container;
use Slim\Views\Twig;
use Slim\Factory\AppFactory;
use App\Factory\DatabaseManager;

AppFactory::setContainer($container);

$container->set('settings', function () {
    $settings = [];

    if ($url = getenv('DATABASE_URL')) {
        $dbopts = parse_url($url);

        $settings['db'] = [
            'driver'   => 'pgsql',
            'user' => $dbopts["user"],
            'password' => $dbopts["pass"],
            'host' => $dbopts["host"],
            'port' => $dbopts["port"],
            'dbname' => ltrim($dbopts["path"],'/')
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