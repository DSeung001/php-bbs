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

        if (isset($postIdx) && isset($name) && isset($pw) && isset($content)) {
            if ($this->reply->store($postIdx, $name, $pw, $content)) {
                $this->redirect('/bbs/post/read?idx=' . $postIdx, '댓글이 작성되었습니다.');
            } else {
                $this->redirectBack('댓글 작성에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    public function read(){
        $replyIdx = $_GET['reply_idx'];

        if (isset($replyIdx)){
            $result = $this->reply->read($replyIdx);
            if ($result !== false) {
                $this->echoJson(['result' => true, 'data' => $result]);
            } else {
                $this->echoJson(['result' => false, 'msg' => '댓글을 불러오는데 실패했습니다.']);
            }
        } else{
            $this->echoJson(['result' => false, 'msg' => '입력 값이 올바르지 않습니다.']);
        }
    }
}