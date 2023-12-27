<!doctype html>
<?php

use Model\Post;

include "part/header.php";
?>
<body>
<div class="m-4">
    <div class="container mt-5">
        <h3 class="d-inline"><a href="/bbs">자유게시판</a></h3>/<h4 class="d-inline">글 읽기</h4>
        <p class="mt-1 mb-3">글의 상세 내용입니다.</p>
        <hr/>
        <?php
        $idx = $_GET['idx'];
        $post = new Post();

        $postInfo = $post->getPost($idx);

        if ($postInfo) {
            // 게시글의 락 여부와 락 쿠키 체크
            $pass = false;
            if (isset($_COOKIE['post_key' . $postInfo['idx']])
                && password_verify($_COOKIE['post_key' . $postInfo['idx']], $postInfo['pw'])) {
                $pass = true;
            }
            if ($postInfo['lock'] == 1 && !$pass) {
                ?>
                <form action="/bbs/post/lockCheck" method="post">
                    <p>비밀글입니다, 보기 위해서는 비밀번호가 필요합니다.</p>
                    <div class="form-group">
                        <input type="hidden" name="idx" value="<?= $idx ?>">
                        <label for="pw">Password</label>
                        <input id="pw" type="text" class="form-control" name="pw" placeholder="비밀번호를 입력하세요">
                    </div>
                    <button type="submit" class="btn btn-primary">확인하기</button>
                    <a href="/bbs" class="btn btn-secondary">목록</a>
                </form>
                <?php
            } else {
                $viewsBonus = 0;
                if (!isset($_COOKIE['post_views' . $idx])) {
                    $post->increaseViews($idx);
                    $viewsBonus = 1;
                }
                ?>
                <div>
                    <h5 class="d-inline">제목) <?= $postInfo['title'] ?></h5>
                    <p class="float-right">글쓴이) <?= $postInfo['name'] ?></p>
                </div>
                <span class="mr-2">작성일: <?= $postInfo['created_at'] ?></span>
                <span class="mr-2">수정일: <?= $postInfo['updated_at'] ?></span>
                <span class="mr-2">조회수: <?= $postInfo['views'] + $viewsBonus ?></span>
                <span class="mr-2">추천수: <?= $postInfo['thumbs_up'] ?></span>
                <hr/>

                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">
                            <?= nl2br($postInfo['content']) ?>
                        </p>
                    </div>
                </div>

                <a href="./update?idx=<?= $postInfo['idx'] ?>" class="btn btn-primary">수정하기</a>
                <a href="/bbs" class="btn btn-secondary">목록</a>
                <a href="./delete?idx=<?= $postInfo['idx'] ?>" class="btn btn-dark">삭제하기</a>
                <button class="btn btn-success" id="thumbsUp">
                    추천 <?= $postInfo['thumbs_up'] != 0 ? "(" . $postInfo['thumbs_up'] . ")" : '' ?>
                    <span class="material-symbols-outlined" style="font-size:16px">thumb_up</span>
                </button>
                <!--추천에서 사용할 postIdx 값-->
                <input type="hidden" id="postIdx" value="<?= $idx ?>">
                <?php
            }
        } else {
            echo "<script>alert('존재하지 않는 게시물입니다.');history.back();</script>";
        }
        ?>
    </div>
    <script src="/bbs/assets/script/read.js"></script>
</body>
</html>