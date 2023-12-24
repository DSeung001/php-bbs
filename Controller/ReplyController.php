<?php

namespace Controller;

use Model\Reply;

class ReplyController extends Controller
{
    private $reply;

    public function __construct()
    {
        $this->reply = new reply();
    }

    public function create()
    {
        $postIdx = $_POST['post_idx'];
        $name = $_POST['name'];
        $pw = $_POST['pw'];
        $content = $_POST['content'];
        $parentIdx = $_POST['parent_idx'];

        if ($this->parametersCheck($postIdx, $name, $pw, $content)) {
            if (empty($parentIdx)) {
                if ($this->reply->store($postIdx, $name, $pw, $content)) {
                    $this->redirect('/bbs/post/read?idx=' . $postIdx, '댓글이 작성되었습니다.');
                } else {
                    $this->redirectBack('댓글 작성에 실패했습니다.');
                }
            } else {
                if ($this->reply->subReplyStore($postIdx, $parentIdx, $name, $pw, $content)) {
                    $this->redirect('/bbs/post/read?idx=' . $postIdx, '댓글이 작성되었습니다.');
                } else {
                    $this->redirectBack('댓글 작성에 실패했습니다.');
                }
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    public function read()
    {
        $replyIdx = $_GET['reply_idx'];

        if ($this->parametersCheck($replyIdx)) {
            $this->echoJson($this->reply->read($replyIdx));
        } else {
            $this->echoJson(['result' => false, 'msg' => '입력 값이 올바르지 않습니다.']);
        }
    }

    public function update()
    {
        $replyIdx = $_POST['reply_idx'];
        $pw = $_POST['pw'];
        $content = $_POST['content'];

        if ($this->parametersCheck($replyIdx, $pw, $content)) {
            $this->echoJson($this->reply->update($replyIdx, $pw, $content));
        } else {
            $this->echoJson(['result' => false, 'msg' => '입력되지 않은 값이 있습니다.']);
        }
    }

    public function delete()
    {
        $replyIdx = $_POST['reply_idx'];
        $pw = $_POST['pw'];

        if ($this->parametersCheck($replyIdx, $pw)) {
            $this->echoJson($this->reply->delete($replyIdx, $pw));
        } else {
            $this->echoJson(['result' => false, 'msg' => '입력되지 않은 값이 있습니다.']);
        }
    }
}