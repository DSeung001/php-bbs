<?php

namespace Controller;
require_once "../bootstrap.php";

use Model\Reply;

class ReplyController extends BaseController
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

        if (isset($postIdx) && isset($name) && isset($pw) && isset($content)) {
            if ($this->reply->store($postIdx, $name, $pw, $content)) {
                $this->redirect('/bbs/view/read.php?idx=' . $postIdx, '댓글이 작성되었습니다.');
            } else {
                $this->redirectBack('댓글 작성에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }
}

function replyRoute()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $protocolHost = $protocol . '://' . $host;
    $ReplyController = new ReplyController();

    if (
        strpos($_SERVER['HTTP_REFERER'], $protocolHost . "/bbs/view/read.php") !== false
        && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $ReplyController->create();
    } else {
        $ReplyController->redirectBack('잘못된 접근입니다.');
    }
}

replyRoute();
