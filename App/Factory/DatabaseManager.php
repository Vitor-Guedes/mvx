<?php

namespace App\Factory;

use Illuminate\Database\Capsule\Manager;
use Psr\Container\ContainerInterface;

class DatabaseManager
{
    public $capsule;

    public function __construct(ContainerInterface $container)
    {
        $settings = $container->get('settings');
        $dbSettings = $settings['db'];
        
        $manager = new Manager();
        $manager->addConnection($dbSettings);
        $manager->getConnection()->enableQueryLog();
        $manager->setAsGlobal();
        $manager->bootEloquent();

        $this->capsule = $manager;
    }
}