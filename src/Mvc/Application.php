<?php

namespace maderarasto\Mvc;

use Mvc\Facade;
use Mvc\Http\Request;

class Application
{
    protected array $bindings = [];

    public function __construct()
    {
        Facade::setFacadeApplication($this);
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
        $this->bind('request', new Request());
        var_dump(\Mvc\Facades\Request::method());
    }
}

