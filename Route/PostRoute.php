<?php
namespace Route;

use Controller\PostController;

class PostRoute extends Route
{
    function routing($url)
    {
        $PostController = new PostController();

        // 글 작성 핸들링
        if ($this->routeCheck($url,"post/create", "POST")) {
            $PostController->create();
        } else if (
            $this->routeCheck($url,"post/update", "POST")) {
            $PostController->update();
        } else if (
            $this->routeCheck($url,"post/delete", "POST")) {
            $PostController->delete();
        } else if (
            $this->routeCheck($url,"post/lockCheck", "POST")) {
            $PostController->lockCheck();
        }
    }
}