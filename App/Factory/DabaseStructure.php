<?php

namespace App\Factory;

class DabaseStructure
{
    const BASE_NAMESPACE = '\\App\\Database\\';

    /**
     * @param string $type - [Migrations, Seederes]
     * @param string $table
     * @return mixed|bool
     */
    public static function create(string $type, string $table)
    {
        $typeMethod = "createFor{$type}";
        return static::$typeMethod(ucfirst($table));
    }

    protected static function createForMigrations(string $table)
    {
        $table = "Migrations\\Create{$table}Table";
        return static::getInstance(static::BASE_NAMESPACE . $table);
    }

    protected static function createForSeeders(string $table)
    {
        $table = "Seeders\\{$table}Seeders";
        return static::getInstance(static::BASE_NAMESPACE . $table);
    }

    protected static function getInstance(string $className)
    {
        if (class_exists($className)) {
            return new $className();
        }

        return false;
    }
}