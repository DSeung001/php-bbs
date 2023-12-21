<?php

namespace Route;

use Controller\ReplyController;

class ReplyRoute extends Route
{
    function routing($url): bool
    {
        $ReplyController = new ReplyController();

        if ($this->routeCheck($url, "reply/create", "POST")) {
            $ReplyController->create();
            return true;
        } else {
            return false;
        }
    }
}