<!doctype html>
<?php
require_once "../bootstrap.php";
use DB\Connection;

include "part/header.php";
?>
<body>
<?php
$conn = new connection();
$conn = $conn->getConnection();
$stmt = $conn->prepare("select * from posts where idx = :idx");
$stmt->bindParam('idx', $_GET['idx']);
$post = $stmt->execute();
$post = $stmt->fetch();
if ($post) {
    ?>
    <div class="m-4">
        <div class="container mt-5">
            <h3 class="d-inline"><a href="/bbs/view">자유게시판</a></h3>/<h4 class="d-inline">글 삭제</h4>
            <p class="mt-1">글을 삭제하는 공간입니다.</p>

            <form action="/bbs/post/delete" method="post">
                <span class="mr-2">작성일: <?= $post['created_at'] ?></span>
                <span class="mr-2">수정일: <?= $post['updated_at'] ?></span>
                <span class="mr-2">조회수: <?= $post['hit'] ?></span>
                <span class="mr-2">추천수: <?= $post['thumbs_up'] ?></span>

                <input type="hidden" name="idx" value="<?= $_GET['idx'] ?>">

                <div class="form-group mt-3">
                    <label for="title">제목</label>
                    <p><?= $post['title'] ?></p>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <p> <?= $post['name'] ?></p>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="pw" placeholder="Password를 입력해주세요.">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">삭제</button>
                <a href="/bbs/view" class="btn btn-secondary">목록</a>
                <a href="/bbs/view/read.php?idx=<?= $post['idx'] ?>" class="btn btn-secondary">뒤로가기</a>
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