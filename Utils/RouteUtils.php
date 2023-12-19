<?php

namespace utils;

trait RouteUtils{
    public function routeCheck($path, $method): bool
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $protocolHost = $protocol . '://' . $host;

        return strpos($_SERVER['HTTP_REFERER'], $protocolHost . "$path") !== false
            && $_SERVER['REQUEST_METHOD'] == $method;
    }
}
