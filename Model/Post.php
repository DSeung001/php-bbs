<?php
namespace Model;

use PDOException;

class Post extends BaseModel
{
    public function __construct()
    {
        parent::__construct();
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

    public function update($idx, $pw, $title, $content, $lock)
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

    public function delete ($idx, $pw){
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

    public function lockCheck($idx, $pw)
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
            setcookie("post_key". $idx, $pw, time() + 3600, "/") ;
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function thumbsUp($idx){
        try{
            if (isset($_COOKIE["post_thumbs_up". $idx])){
                return false;
            }

            $query = "UPDATE posts SET thumbs_up = thumbs_up + 1 WHERE idx = :idx";
            $result = $this->conn->prepare($query)->execute([
                'idx' => $idx
            ]);
            if ($result){
                setcookie("post_thumbs_up". $idx, true, time() + 3600, "/") ;
            }
            return $result;
        } catch (PDOException  $e){
            error_log($e->getMessage());
            return false;
        }
    }
}