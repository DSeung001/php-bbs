<?php

namespace Route;

use Controller\Controller;
use Utils\RouteUtils;

abstract class Route
{
    use RouteUtils;

    abstract function routing($url): bool;
}