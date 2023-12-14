<?php
session_start();

// 한글 깨짐 방지 UTF
header('Content-type: text/html; charset=utf-8');

$db_hostname = getenv("DB_HOSTNAME");
$db_user=getenv("DB_USER");
$db_password=getenv("DB_PASSWORD");
$db_name=getenv("DB_NAME");

$db = new mysqli($db_hostname, $db_user, $db_password, $db_name);
$db->set_charset("utf8");

function query($query)
{
    global $db;
    return $db->query($query);
}
