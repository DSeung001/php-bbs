<?php
namespace Route;

use Controller\Controller;
use Utils\RouteUtils;

abstract class Route {
    use RouteUtils;
    private $controller;

    public function __construct(Controller $controller)
    {
        $this->controller = $controller;
    }

    abstract function routing($url);
}