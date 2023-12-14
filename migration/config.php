<?php
$config =  parse_ini_file('../config.ini');

try {
    // 기본 연결 정보로 접속하여 존재 여부 확인
    $dsn = "mysql:host={$config['DB_HOSTNAME']};charset=utf8";
    $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 데이터베이스가 이미 존재하는지 확인하는 쿼리
    $result = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$config['DB_NAME']}'");

    if ($result->rowCount() == 0) {
        // 데이터베이스가 존재하지 않으면 생성
        $pdo->exec("CREATE DATABASE {$config['DB_NAME']}");
        echo "Database {$config['DB_NAME']} created successfully.<br/>";
    } else {
        echo "Database {$config['DB_NAME']} already exists.<br/>";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

return $config;