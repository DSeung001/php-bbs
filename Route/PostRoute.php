<?php

namespace Route;

use Controller\PostController;

class PostRoute extends Route
{
    function routing($url): bool
    {
        $PostController = new PostController();

        if ($this->routeCheck($url, "post/list", "GET")) {
            require_once(__DIR__ . '/../view/index.php');
            return true;
        } else if ($this->routeCheck($url, "post/create", "GET")) {
            require_once(__DIR__ . '/../view/create.php');
            return true;
        } else if ($this->routeCheck($url, "post/read", "GET")) {
            require_once(__DIR__ . '/../view/read.php');
            return true;
        } else if ($this->routeCheck($url, "post/update", "GET")) {
            require_once(__DIR__ . '/../view/update.php');
            return true;
        } else if ($this->routeCheck($url, "post/delete", "GET")) {
            require_once(__DIR__ . '/../view/delete.php');
            return true;
        } else if ($this->routeCheck($url, "post/create", "POST")) {
            $PostController->create();
            return true;
        } else if ($this->routeCheck($url, "post/update", "POST")) {
            $PostController->update();
            return true;
        } else if ($this->routeCheck($url, "post/delete", "POST")) {
            $PostController->delete();
            return true;
        } else if ($this->routeCheck($url, "post/lockCheck", "POST")) {
            $PostController->lockCheck();
            return true;
        } else if ($this->routeCheck($url, "post/thumbsUp", "POST")) {
            $PostController->thumbsUp();
            return true;
        } else {
            return false;
        }
    }
}