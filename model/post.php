<?php

namespace model;

require_once("../db/connection.php");

use db\connection;
use PDO;
use PDOException;

class post
{
    private $conn;

    public function __construct()
    {
        $this->conn = new connection();
        $this->conn = $this->conn->getConnection();
    }

    public function store($name, $pw, $title, $content)
    {
        try {
            $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);
            $query = "INSERT INTO posts (name, pw, title,content) VALUES (:name, :pw, :title, :content)";
            return $this->conn->prepare($query)->execute([
                'name' => $name,
                'pw' => $hashed_pw,
                'title' => $title,
                'content' => $content
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update($idx, $pw, $title, $content)
    {
        try {
            $query = "SELECT pw FROM posts WHERE idx = :idx";
            $check = $this->conn->prepare($query);
            $check->bindParam(':idx', $idx)->fetch();

            // 비밀번호 체크
            if (!$check || password_verify($pw, $check['pw'])) {
                return false;
            }

            // 업데이트
            $query = "UPDATE posts SET title = :title, content = :content, updated_at = :updated_at WHERE idx = :idx";
            echo $idx." ".$pw." ".$title." ".$content;
            return $this->conn->prepare($query)->execute([
                'title' => $title,
                'content' => $content,
                'updated_at' => date('Y-m-d H:i:s'),
                'idx' => $idx,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}