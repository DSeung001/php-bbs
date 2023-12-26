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

    /**
     * @param $idx
     * @param $pw
     * @param $title
     * @param $content
     * @param $lock
     * @return bool
     */
    public function update($idx, $pw, $title, $content, $lock): bool
    {
        try {
            $query = "SELECT pw FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return false;
            }
            // 업데이트
            $query = "UPDATE posts SET title = :title, content = :content, `lock` = :lock WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'title' => $title,
                'content' => $content,
                'lock' => $lock == 'on' ? 1 : 0,
                'idx' => $idx,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $idx
     * @param $pw
     * @return bool
     */
    public function delete($idx, $pw): bool
    {
        try {
            $query = "SELECT pw FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return false;
            }
            // 삭제
            $query = "DELETE FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([
                'idx' => $idx,
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $idx
     * @param $pw
     * @return bool
     */
    public function lockCheck($idx, $pw): bool
    {
        try {
            echo $idx;
            $query = "SELECT pw FROM posts WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return false;
            }
            setcookie("post_key" . $idx, $pw, time() + 3600, "/");
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $idx
     * @return array
     */
    public function thumbsUp($idx): array
    {
        try {
            if (isset($_COOKIE["post_thumbs_up" . $idx])) {
                return [
                    'result' => false,
                    'msg' => '이미 추천하셨습니다.'
                ];
            }

            $query = "UPDATE posts SET thumbs_up = thumbs_up + 1 WHERE idx = :idx";
            $result = $this->conn->prepare($query)->execute([
                'idx' => $idx
            ]);
            if ($result) {
                setcookie("post_thumbs_up" . $idx, true, time() + 3600, "/");
                return [
                    'result' => true,
                    'msg' => '추천되었습니다.'
                ];
            }
            return [
                'result' => false,
                'msg' => '추천에 실패했습니다.'
            ];
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return [
                'result' => false,
                'msg' => '알 수 없는 에러가 발생했습니다, 관리자에게 문의주세요.'
            ];
        }
    }

    /**
     * @param $idx
     * @return array|mixed
     */
    public function getPost($idx)
    {
        try {
            $query = "SELECT * FROM posts WHERE idx = :idx LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $idx
            ]);
            return $stmt->fetch();
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $idx
     * @return bool|void
     */
    public function increaseViews($idx)
    {
        try {
            if (!isset($_COOKIE['post_views' . $idx])) {
                $stmt = $this->conn->prepare("update posts set views = views + 1 where idx = :idx");
                $stmt->bindParam('idx', $idx);
                $stmt->execute();
                setcookie('post_views' . $idx, true, time() + 60 * 60 * 24, '/');
                return true;
            }
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $search
     * @return int|mixed
     */
    public function count($search)
    {
        try {
            $query = "SELECT count(idx) FROM posts WHERE title like :search";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue('search', '%' . ($search ?? '') . '%');
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException  $e) {
            error_log($e->getMessage());
            return 0;
        }
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