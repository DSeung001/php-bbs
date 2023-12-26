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
     * @param $search
     * @param $start
     * @param $perPage
     * @return array|false
     */
    public function getPosts($search, $start, $perPage)
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
}