<?php

namespace Migration;
require_once "../bootstrap.php";
use DB\Connection;
use PDOException;

class Reply
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
            $tableName = "replies";

            // 테이블이 존재하는지 확인
            $checkTableExists = $this->conn->query("SHOW TABLES LIKE '$tableName'")->rowCount() > 0;

            // 테이블이 존재하지 않으면 테이블 생성
            if (!$checkTableExists) {
                $createTableSQL = "CREATE TABLE $tableName (
            idx INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            parent_idx INT(6) UNSIGNED,
            post_idx INT(6) UNSIGNED NOT NULL,
            name VARCHAR(100) NOT NULL,
            pw VARCHAR(100) NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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