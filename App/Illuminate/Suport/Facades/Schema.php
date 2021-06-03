<?php

namespace App\Illuminate\Suport\Facades;

use Illuminate\Support\Facades\Schema as FacadesSchema;

class Schema
extends FacadesSchema
{
    public static function connection($name)
    {
        return static::$app->getContainer()->get('db')->connection($name)->getSchemaBuilder();
    }

    protected static function getFacadeAccessor()
    {
        return static::$app->getContainer()->get('db')->connection()->getSchemaBuilder();
    }
}