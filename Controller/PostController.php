<?php
namespace Controller;

use Model\Post;

class PostController extends BaseController
{
    private $post;

    // 생성자를 통해 PostModel 객체 생성
    public function __construct()
    {
        $this->post = new Post();
    }

    /**
     * 게시글 생성 기능을 담당
     * 데이터 유효성 검사 + Post Model을 통해 데이터 생성을
     * @return void
     */
    public function create()
    {
        // POST 데이터 정리
        $name = $_POST['name'];
        $pw = $_POST['pw'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        // 데이터 유효성 검사
        if ($this->parametersCheck($name,$pw,$title,$content)) {
            // POST 데이터 생성
            if ($this->post->create($name, $pw, $title, $content)) {
                $this->redirect('/bbs', '글이 작성되었습니다.');
            } else {
                $this->redirectBack('글 작성에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }
}