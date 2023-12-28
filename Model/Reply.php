<?php

namespace Model;

use PDOException;

class Reply extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 댓글 만들기
     * @param $postIdx
     * @param $name
     * @param $pw
     * @param $content
     * @return bool
     */
    public function create($postIdx, $name, $pw, $content): bool
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

    /**
     * 댓글 목록 가져오기
     * @param $postIdx
     * @return array
     */
    public function getReplies($postIdx): array
    {
        try {
            $query = "SELECT * FROM replies WHERE post_idx = :post_idx AND parent_idx = 0 ORDER BY idx DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'post_idx' => $postIdx,
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}