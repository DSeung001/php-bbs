<?php

namespace controller;

use model\post;
require_once('../model/post.php');

route();
function route()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $protocolHost = $protocol . '://' . $host;
    $postController = new postController();

    // 글 작성 핸들링
    if ($_SERVER['HTTP_REFERER'] == $protocolHost . "/bbs/view/create.php"
        && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $postController->create();
    } else if (
        strpos($_SERVER['HTTP_REFERER'], $protocolHost . "/bbs/view/modify.php") !== false
        && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $postController->update();
    } else {
        $postController->redirectBack('잘못된 접근입니다.');

    }
}

class postController
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
            // 패스워드 암호화
            if ($this->post->store($name, $pw, $title, $content)) {
                $this->redirect('/bbs/view', '글이 작성되었습니다.');
            }
            else {
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

        if (isset($idx) && isset($pw) && isset($title) && isset($content)) {
            // 패스워드 암호화
            require_once '../model/post.php';
            $this->post = new post();

            if ($this->post->update($idx, $pw, $title, $content)) {
                $this->redirect('/bbs/view', '글이 수정되었습니다.');
            } else {
                $this->redirectBack('글 수정에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    public function redirect($path, $message)
    {
        echo "<script>
                alert('$message');
                location.href='$path';
              </script>";
        exit();
    }

    public function redirectBack($message)
    {
        echo "<script>
                alert('$message');
                history.back();
              </script>";
        exit();
    }
}