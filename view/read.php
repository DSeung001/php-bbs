<!doctype html>
<?php
require_once("../db/connection.php");

use db\connection;

include "part/header.php";
?>
<body>
<div class="m-4">
    <div class="container mt-5">
        <h3 class="d-inline"><a href="/bbs/view">자유게시판</a></h3>/<h4 class="d-inline">글 읽기</h4>
        <p class="mt-1 mb-3">글의 상세 내용입니다.</p>
        <hr/>
        <?php
        $conn = new connection();
        $conn = $conn->getConnection();
        $stmt = $conn->prepare("select * from posts where idx = :idx");
        $stmt->bindParam('idx', $_GET['idx']);
        $post = $stmt->execute();
        $post = $stmt->fetch();

        if ($post) {
            // 게시글의 락 여부와 락 쿠키 체크
            $pass = false;
            if (isset($_COOKIE['post_key' . $post['idx']])
                && password_verify($_COOKIE['post_key' . $post['idx']], $post['pw'])) {
                $pass = true;
            }
            if ($post['lock'] == 1 && !$pass) {
                ?>
                <form action="../controller/postController.php" method="post">
                    <p>비밀글입니다, 보기 위해서는 비밀번호가 필요합니다.</p>
                    <div class="form-group">
                        <input type="hidden" name="idx" value="<?= $_GET['idx'] ?>">
                        <label for="pw">Password</label>
                        <input id="pw" type="text" class="form-control" name="pw" placeholder="비밀번호를 입력하세요">
                    </div>
                    <button type="submit" class="btn btn-primary">확인하기</button>
                </form>
                <?php
            } else {
                ?>
                <div>
                    <h5 class="d-inline"><b>제목)</b> <?= $post['title'] ?></h5>
                    <p class="float-right"><b>글쓴이)</b> <?= $post['name'] ?></p>
                </div>
                <span class="mr-2">작성일: <?= $post['created_at'] ?></span>
                <span class="mr-2">수정일: <?= $post['updated_at'] ?></span>
                <span class="mr-2">조회수: <?= $post['hit'] ?></span>
                <span class="mr-2">추천수: <?= $post['thumbs_up'] ?></span>
                <hr/>

                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">
                            <?= nl2br($post['content']) ?>
                        </p>
                    </div>
                </div>

                <a href="/bbs/view/update.php?idx=<?= $post['idx'] ?>" class="btn btn-primary">수정하기</a>
                <a href="/bbs/view/delete.php?idx=<?= $post['idx'] ?>" class="btn btn-dark">삭제하기</a>
                <a href="#" class="btn btn-secondary">댓글 달기</a>

                <!-- 댓글 섹션 예시 -->
                <div class="mt-4">
                    <h3>댓글</h3>
                    <div class="media mt-3">
                        <!--https://placehold.co/로 이미지 가져오기-->
                        <img src="https://via.placeholder.com/64" class="mr-3 rounded-circle" alt="유저 이미지">
                        <div class="media-body">
                            <h5 class="mt-0">댓글 작성자</h5>
                            댓글 내용이 여기에 들어갑니다.
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<script>alert('존재하지 않는 게시물입니다.');history.back();</script>";
        }
        ?>
    </div>
</div>
</body>
</html>