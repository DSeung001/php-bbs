<?php

namespace Model;

use PDOException;

class Reply extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function store($postIdx, $name, $pw, $content)
    {
        try {
            $hashed_pw = password_hash($pw, PASSWORD_DEFAULT);
            $query = "INSERT INTO replies (post_idx, name, pw, content) VALUES (:post_idx, :name, :pw, :content)";
            return $this->conn->prepare($query)->execute([
                'post_idx' => $postIdx,
                'name' => $name,
                'pw' => $hashed_pw,
                'content' => $content
            ]);
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function read($replyIdx)
    {
        try {
            $query = "SELECT name, content FROM replies WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $replyIdx,
            ]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}