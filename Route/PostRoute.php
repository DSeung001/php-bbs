<?php

namespace Route;

class PostRoute extends BaseRoute
{
    function routing($url): bool
    {
        // 게시글 목록에 대한 라우팅
        if ($this->routeCheck($url, "post/list", "GET")) {
            return $this->requireView('index');
        } else if ($this->routeCheck($url, "post/create", "GET")) {
            return $this->requireView('create');
        }else {
            return false;
        }
    }
}