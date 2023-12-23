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
     * @param $postIdx
     * @param $name
     * @param $pw
     * @param $content
     * @return bool
     */
    public function store($postIdx, $name, $pw, $content): bool
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
     * @param $replyIdx
     * @return array
     */
    public function read($replyIdx): array
    {
        try {
            $query = "SELECT name, content FROM replies WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $replyIdx,
            ]);
            $data = $stmt->fetch();
            return [
                'result' => (bool)$data,
                'data' => $data
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'result' => false,
                'msg' => '알 수 없는 에러가 발생했습니다, 관리자에게 문의주세요.'
            ];
        }
    }

    /**
     * @param $replyIdx
     * @param $pw
     * @param $content
     * @return array
     */
    public function update($replyIdx, $pw, $content): array
    {
        try {
            $query = "SELECT pw FROM replies WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $replyIdx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return [
                    'result' => false,
                    'msg' => '비밀번호가 일치하지 않습니다.'
                ];
            }
            // 업데이트
            $query = "UPDATE replies SET content = :content WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                'content' => $content,
                'idx' => $replyIdx,
            ]);
            return [
                'result' => $result,
                'msg' => $result ? '댓글이 수정되었습니다.' : '댓글 수정이 실패했습니다.'
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'result' => false,
                'msg' => '알 수 없는 에러가 발생했습니다, 관리자에게 문의주세요.'
            ];
        }
    }

    /**
     * @param $replyIdx
     * @param $pw
     * @return array
     */
    public function delete($replyIdx, $pw): array
    {
        try {
            $query = "SELECT pw FROM replies WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                'idx' => $replyIdx,
            ]);
            $check = $stmt->fetch();

            // 비밀번호 체크
            if (!$check || !password_verify($pw, $check['pw'])) {
                return [
                    'result' => false,
                    'msg' => '비밀번호가 일치하지 않습니다.'
                ];
            }
            // 삭제
            $query = "DELETE FROM replies WHERE idx = :idx";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                'idx' => $replyIdx,
            ]);
            return [
                'result' => $result,
                'msg' => $result ? '댓글이 삭제되었습니다.' : '댓글 삭제를 실패했습니다.'
            ];
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'result' => false,
                'msg' => '알 수 없는 에러가 발생했습니다, 관리자에게 문의주세요.'
            ];
        }
    }
}