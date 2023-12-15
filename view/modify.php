<!doctype html>
<?php
include "part/header.php";
?>
<body>
<?php
$conn = require_once $_SERVER['DOCUMENT_ROOT'] . "/bbs/db/connection.php";
$post = $conn->query("select * from posts where idx = {$_GET['idx']}")->fetch();
if ($post) {
    ?>
    <div class="m-4">
        <div class="container mt-5">
            <h3 class="d-inline"><a href="/bbs/view">자유게시판</a></h3>/<h4 class="d-inline">글 수정</h4>
            <p class="mt-1">글을 수정하는 공간입니다.</p>

            <form action="../controller/postController.php" method="post">
                <span class="mr-3">작성일: <?= $post['created_at'] ?></span>
                <span class="mr-3">조회수: <?= $post['hit'] ?></span>
                <span class="mr-3">추천수: <?= $post['thumbs_up'] ?></span>

                <div class="form-group mt-3">
                    <label for="title">제목</label>
                    <input type="text" class="form-control" name="title" placeholder="제목을 입력하세요" value="<?= $post['title']?>">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <p class="form-control"> <?= $post['name'] ?></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="pw" placeholder="Password를 입력해주세요.">
                    </div>
                </div>

                <div class="form-group">
                    <label for="content">내용</label>
                    <textarea class="form-control" name="content" rows="5" placeholder="내용을 입력하세요"><?= $post['content'] ?>
                    </textarea>
                </div>

                <button type="submit" class="btn btn-primary">저장하기</button>
                <a href="/bbs/view" class="btn btn-secondary">목록</a>
                <a href="/bbs/view/read.php?idx=<?= $post['idx']?>" class="btn btn-secondary">뒤로가기</a>
            </form>
        </div>
    </div>
    <?php
} else {
    echo "<script>alert('존재하지 않는 게시물입니다.');history.back();</script>";
}
?>
</body>
</html>