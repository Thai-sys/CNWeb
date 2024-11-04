<?php
// src/Core/Router.php

class Router
{
    private $routes = [];

    public function add($method, $route, $controller)
    {
        $this->routes[$method][$route] = $controller;
    }

    public function route($uri, $method)
    {
        if (isset($this->routes[$method][$uri])) {
            return $this->routes[$method][$uri];
        }

        return null;
    }
}
