<?php
require_once "bootstrap.php";

use Controller\PostController;
use Controller\ReplyController;
use Route\PostRoute;
use Route\ReplyRoute;

$url = isset($_GET['url']) ? $_GET['url'] : '/';
$url = rtrim($url, '/'); // 끝에 있는 슬래시 제거

// => url로 하위 패스 값이 왔다 이걸로 잘 route를 만들어보자

if ($url == '/') {
    header('Location: view/index.php');
}
echo $url . '<br>';
echo $_SERVER['REQUEST_METHOD'];

$routes = array();
$routes[] = new PostRoute(new PostController());
$routes[] = new ReplyRoute(new ReplyController());

foreach ($routes as $route){
    $route->routing($url);
}

// 리다이렉트 to 404 page

