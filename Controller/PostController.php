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

    /**
     * 게시글 수정 기능을 담당
     * @return void
     */
    public function update()
    {
        $idx = $_POST['idx'];
        $pw = $_POST['pw'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $lock = $_POST['lock'];

        if ($this->parametersCheck($idx, $pw, $title, $content)) {
            if ($this->post->update($idx, $pw, $title, $content, $lock)) {
                $this->redirect('/bbs', '글이 수정되었습니다.');
            } else {
                $this->redirectBack('글 수정에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    /**
     * 게시글 삭제 기능을 담당
     * @return void
     */
    public function delete()
    {
        $idx = $_POST['idx'];
        $pw = $_POST['pw'];

        if ($this->parametersCheck($idx, $pw)) {
            if ($this->post->delete($idx, $pw)) {
                $this->redirect('/bbs', '글이 삭제되었습니다.');
            } else {
                $this->redirectBack('글 삭제에 실패했습니다.');
            }
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }

    /**
     * 추천 기능을 담당
     * @return void
     */
    public function thumbsUp()
    {
        $postIdx = $_POST['post_idx'];

        if ($this->parametersCheck($postIdx)) {
            $this->echoJson($this->post->thumbsUp($postIdx));
        } else {
            $this->redirectBack('입력되지 않은 값이 있습니다.');
        }
    }
}