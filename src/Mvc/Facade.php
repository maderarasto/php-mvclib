<?php

namespace Mvc;

use maderarasto\Mvc\Application;

abstract class Facade
{
    protected static Application $app;

    public static function setFacadeApplication($app)
    {
        static::$app = $app;
    }

    public static function __callStatic(string $name, array $arguments)
    {
        if (!static::$app) {
            throw new \Exception('Application not set');
        }

        $binding = static::$app->getBinding(static::getFacadeAccessor());

        if (!$binding) {
            throw new \Exception('Binding not found');
        }

        if (!method_exists($binding, $name)) {
            throw new \Exception('Method not found');
        }

        return $binding->$name(...$arguments);
    }

     protected static function getFacadeAccessor() : string
     {
         throw new \Exception('Facade accessor not implemented');
     }
}