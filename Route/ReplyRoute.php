<?php

namespace Route;

use Controller\ReplyController;

class ReplyRoute extends Route
{
    function routing($url): bool
    {
        $replyController = new ReplyController();

        if ($this->routeCheck($url, "reply/create", "POST")) {
            $replyController->create();
            return true;
        } else if($this->routeCheck($url, "reply/read", "GET")){
            $replyController->read();
            return true;
        } else{
            return false;
        }
    }
}