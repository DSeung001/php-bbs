<?php
require_once "bootstrap.php";

use Controller\PostController;
use Controller\ReplyController;
use Route\PostRoute;
use Route\ReplyRoute;

$url = isset($_GET['url']) ? $_GET['url'] : '/';

if ($url == '/' || $url == '') {
    echo "실행";
    header('Location: post/list');
} else {

    $routes = array();
    $routes[] = new PostRoute(new PostController());
    $routes[] = new ReplyRoute(new ReplyController());

    $ok = false;
    foreach ($routes as $route) {
        $ok = $route->routing($url);
        if ($ok) {
            break;
        }
    }

    if (!$ok) {
        header("HTTP/1.0 404 Not Found");
        require_once "view/404.php";
    }
}