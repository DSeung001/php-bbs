<?php

namespace Migration;

require_once "../bootstrap.php";
use DB\Connection;
use PDOException;

class Post
{
    private $conn;

    public function __construct()
    {
        $this->conn = new connection();
        $this->conn = $this->conn->getConnection();
    }

    function migrate()
    {
        try {
            $tableName = "posts";

            // 테이블이 존재하는지 확인
            $checkTableExists = $this->conn->query("SHOW TABLES LIKE '$tableName'")->rowCount() > 0;

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
                $this->conn->exec($createTableSQL);
                echo "Table $tableName created successfully<br/>";
            } else {
                echo "Table $tableName already exists<br/>";
            }
        } catch
        (PDOException $e) {
            echo "Connection failed: " . $e->getMessage()."<br/>";
        }
    }
}