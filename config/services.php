<?php

define('BASEPATH', dirname(__DIR__));

use DI\Container;
use Slim\Views\Twig;
use Slim\Factory\AppFactory;
use App\Factory\DatabaseManager;
use Illuminate\Support\Facades\Facade;

AppFactory::setContainer($container);

$container->set('settings', function () {
    $settings = [];

    if (getenv('DATABASE_URL')) {
        $settings['db'] = [
            'driver'   => 'pgsql',
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'host' =>  getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'database' => getenv('DB_NAME'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => ''
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
    $twig = Twig::create(BASEPATH . '/views', [
        'debug' => true,
        'cache' => false
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    return $twig;
});

$container->set('db', function (Container $container) {
    $factory = new DatabaseManager($container);
    return new $factory->capsule;
});

$container->set('bootDb', function (Container $container) {
    $_app = ['db' => $container->get('db')];
    Facade::setFacadeApplication($_app);
});