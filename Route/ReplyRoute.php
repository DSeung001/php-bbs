<?php
namespace Route;

use Controller\ReplyController;

class ReplyRoute extends Route{
    function routing($url){
        echo $url . '<br>';
        echo $_SERVER['REQUEST_METHOD'];
        $ReplyController = new ReplyController();

        if ($this->routeCheck($url,"reply/create","POST")) {
            $ReplyController->create();
        }
    }
}