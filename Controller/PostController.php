<?php
namespace Controller;

use Model\Post;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $postcontroller = new PostController();
        $postcontroller->create();
        break;
}

class PostController
{
    public function create()
    {
        $name = $_POST['name'];
        $pw = $_POST['pw'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        if (isset($name) && isset($pw) && isset($title) && isset($content)) {
            // 패스워드 암호화
            $post = new Post();
            if ($post->store($name, $pw, $title, $content)){
                echo "<script>
                    alert('글이 작성되었습니다.');
                    location.href='/bbs/view';
                  </script>";
            }else{
                echo "<script>
                   alert('글 작성에 실패했습니다.');
                    history.back();
                  </script>";
            }
        } else {
            echo "<script>
                    alert('입력되지 않은 값이 있습니다.');
                    history.back();
                  </script>";
        }
    }
}