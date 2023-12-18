<?php
namespace migration;

use db\connection;

require_once("../connection.php");
$conn = new connection();
$conn = $conn->getConnection();

try {
    $tableName = "posts";

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
            lock INT(1) UNSIGNED DEFAULT 0,
            thumbs_up INT(6) UNSIGNED DEFAULT 0,
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
