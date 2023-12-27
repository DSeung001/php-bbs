<?php

namespace Model;

use PDO;
use PDOException;

class Post extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Post 목록을 가져옵니다.
     * @param $search string 검색어
     * @param $start int 시작할 데이터의 인덱스
     * @param $perPage int 페이지마다 보여줄 데이터 수
     * @return array|false
     */
    public function getPosts(string $search, int $start, int $perPage)
    {
        try {
            $query = "select p.*,
        (SELECT COUNT(*) FROM replies r WHERE r.post_idx = p.idx) AS reply_count,
        CASE WHEN TIMESTAMPDIFF(MINUTE, p.created_at, NOW()) <= 1440 THEN 1
        ELSE 0 END AS is_new
        from posts p
        where p.title like :search
        order by idx desc limit :start, :perPage";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue('search', '%' . ($search ?? '') . '%');
            $stmt->bindParam('start', $start, PDO::PARAM_INT);
            $stmt->bindParam('perPage', $perPage, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * Post 추가
     * @param $name
     * @param $pw
     * @param $title
     * @param $content
     * @return bool
     */
    public function create($name, $pw, $title, $content): bool
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
}