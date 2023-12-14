<?php

$config = require 'config.php';


try {
    // 아래 코드가 공통되는게 싫긴한대
    $dsn = "mysql:host={$config['DB_HOSTNAME']};dbname={$config['DB_NAME']};charset=utf8";
    $conn = new PDO($dsn, $config['DB_USER'], $config['DB_PASSWORD']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tableName = "board";

    // 테이블이 존재하는지 확인
    $checkTableExists = $conn->query("SHOW TABLES LIKE '$tableName'")->rowCount() > 0;

    // 테이블이 존재하지 않으면 테이블 생성
    if (!$checkTableExists) {
        $createTableSQL = "CREATE TABLE $tableName (
            idx INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            pw VARCHAR(100) NOT NULL,
            title VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            hit INT(6) UNSIGNED DEFAULT 0,
            lock_post INT(1) UNSIGNED DEFAULT 0,
            boardcol VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $conn->exec($createTableSQL);
        echo "Table $tableName created successfully";
    } else {
        echo "Table $tableName already exists";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
