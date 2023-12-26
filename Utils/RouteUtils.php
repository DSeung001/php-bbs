<?php

namespace Utils;

trait RouteUtils{
    public function routeCheck($origin, $path, $method): bool
    {
        return strpos($origin, $path) !== false
            && $_SERVER['REQUEST_METHOD'] == $method;
    }

    public function requireView($viewName): bool
    {
        require_once(__DIR__ . '/../view/' . $viewName . '.php');
        return true;
    }
}
