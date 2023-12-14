<?php

$root = $_SERVER['DOCUMENT_ROOT'];

// Apache24/htdocs/bbs/config.ini 경로 => 개인 설정 필요
$config = parse_ini_file($root. '/bbs/config.ini');
$conn = null;

try {
    // 기본 연결 정보로 접속하여 존재 여부 확인
    $dsn = "mysql:host={$config['DB_HOSTNAME']};charset=utf8";
    $conn = new PDO($dsn, $config['DB_USER'], $config['DB_PASSWORD']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 데이터베이스가 이미 존재하는지 확인하는 쿼리
    $result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$config['DB_NAME']}'");

    if ($result->rowCount() == 0) {
        // 데이터베이스가 존재하지 않으면 생성
        $conn->exec("CREATE DATABASE {$config['DB_NAME']}");
        echo "Database {$config['DB_NAME']} created successfully.<br/>";
    } else {
        echo "Database {$config['DB_NAME']} already exists.<br/>";
    }

    $conn->query("use ".$config['DB_NAME']);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

return $conn;