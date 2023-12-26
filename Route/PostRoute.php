<?php

namespace Route;

class PostRoute extends Route
{
    function routing($url): bool
    {
        if ($this->routeCheck($url, "post/list", "GET")) {
            return $this->requireView('index');
        } else {
            return false;
        }
    }
}