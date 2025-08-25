<?php

namespace Mvc\Core;

use Mvc\Http\Request;
use Mvc\Routing\Router;

class Application
{
    protected Router $router;
    protected array $bindings = [];

    public function __construct()
    {
        Facade::setFacadeApplication($this);
        $this->router = new Router();
        $this->bind('router', $this->router);
    }

    public function getBinding($abstract)
    {
        return $this->bindings[$abstract];
    }

    public function bind($abstract, $concrete)
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function handleRequest()
    {
        $request = new Request();
        $this->bind('request', $request);

        // TODO: process middlewares

        $route = $this->router->findRoute($request);

        if (!$route) {
            throw new \Exception('Route not found');
        }


    }
}

