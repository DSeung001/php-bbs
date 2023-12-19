<?php
namespace Controller;
require_once "../bootstrap.php";
use Model\Post;
use Utils\RouteUtils;

class PostController extends BaseController
{
    // php 클래스의 속성 값으로 기본 값을 할 수 없음
    private $post;

    // 그래서 생성자를 통해 값을 넣어줘야 함
    public function __construct()
    {
        $this->post = new post();
    }

    public function create()
    {
        $name = $_POST['name'];
        $pw = $_POST['pw'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        if (isset($name) && isset($pw) && isset($title) && isset($content)) {
            if ($this->post->store($name, $pw, $title, $content)) {
                $this->redirect('/bbs/view', '글이 작성되었습니다.');
            } else {
                $this->redirectBack('글 작성에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    public function update()
    {
        $idx = $_POST['idx'];
        $pw = $_POST['pw'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $lock = $_POST['lock'];

        if (isset($idx) && isset($pw) && isset($title) && isset($content) && isset($lock)) {
            if ($this->post->update($idx, $pw, $title, $content, $lock)) {
                $this->redirect('/bbs/view', '글이 수정되었습니다.');
            } else {
                $this->redirectBack('글 수정에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    public function delete()
    {
        $idx = $_POST['idx'];
        $pw = $_POST['pw'];

        if (isset($idx) && isset($pw)) {
            if ($this->post->delete($idx, $pw)) {
                $this->redirect('/bbs/view', '글이 삭제되었습니다.');
            } else {
                $this->redirectBack('글 삭제에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    public function lockCheck()
    {
        $idx = $_POST['idx'];
        $pw = $_POST['pw'];

        if (isset($pw)) {
            if ($this->post->lockCheck($idx, $pw)) {
                $this->redirect('/bbs/view/read.php?idx=' . $idx, '비밀번호가 일치합니다.');
            } else {
                $this->redirectBack('비밀번호가 일치하지 않습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }
}

class PostRoute
{
    use RouteUtils;

    function routing()
    {
        $PostController = new PostController();

        // 글 작성 핸들링
        if ($this->routeCheck("/bbs/view/create.php", "POST")) {
            $PostController->create();
        } else if (
            $this->routeCheck("/bbs/view/update.php", "POST")) {
            $PostController->update();
        } else if (
            $this->routeCheck("/bbs/view/delete.php", "POST")) {
            $PostController->delete();
        } else if (
            $this->routeCheck("/bbs/view/read.php", "POST")) {
            $PostController->lockCheck();
        } else {
            $PostController->redirectBack('잘못된 접근입니다.');
        }
    }
}
$route = new PostRoute();
$route->routing();