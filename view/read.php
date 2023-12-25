<!doctype html>
<?php

use DB\Connection;

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
        $conn = new connection();
        $conn = $conn->getConnection();
        $stmt = $conn->prepare("select * from posts where idx = :idx");
        $stmt->bindParam('idx', $idx);
        $stmt->execute();
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
                $hitBonus = 0;
                if (!isset($_COOKIE['post_hit' . $idx])) {
                    $stmt = $conn->prepare("update posts set hit = hit + 1 where idx = :idx");
                    $stmt->bindParam('idx', $idx);
                    $stmt->execute();
                    setcookie('post_hit' . $post['idx'], true, time() + 60 * 60 * 24, '/');
                    $hitBonus = 1;
                }
                ?>
                <div>
                    <h5 class="d-inline">제목) <?= $post['title'] ?></h5>
                    <p class="float-right">글쓴이) <?= $post['name'] ?></p>
                </div>
                <span class="mr-2">작성일: <?= $post['created_at'] ?></span>
                <span class="mr-2">수정일: <?= $post['updated_at'] ?></span>
                <span class="mr-2">조회수: <?= $post['hit'] + $hitBonus ?></span>
                <span class="mr-2">추천수: <?= $post['thumbs_up'] ?></span>
                <hr/>

                <div class="card mb-3">
                    <div class="card-body">
                        <p class="card-text">
                            <?= nl2br($post['content']) ?>
                        </p>
                    </div>
                </div>

                <a href="./update?idx=<?= $post['idx'] ?>" class="btn btn-primary">수정하기</a>
                <a href="/bbs" class="btn btn-secondary">목록</a>
                <a href="./delete?idx=<?= $post['idx'] ?>" class="btn btn-dark">삭제하기</a>
                <button class="btn btn-success" id="thumbsUp">
                    추천 <?= $post['thumbs_up'] != 0 ? "(" . $post['thumbs_up'] . ")" : '' ?>
                    <span class="material-symbols-outlined" style="font-size:16px">thumb_up</span>
                </button>
                <!--추천에서 사용할 postIdx 값-->
                <input type="hidden" id="postIdx" value="<?= $idx ?>">

                <div class="mt-2">
                    <hr/>
                    <h5>댓글 작성</h5>
                    <form action="/bbs/reply/create" method="post">
                        <div class="form-group">
                            <input type="hidden" name="post_idx" value="<?= $idx ?>">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Name을 입력해주세요.">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="pw"
                                           placeholder="Password를 입력해주세요.">
                                </div>
                            </div>

                            <label for="content">내용:</label>
                            <textarea name="content" class="form-control" id="content" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">댓글 작성</button>
                    </form>
                </div>
                <hr/>
                <h3>댓글</h3>
                <?php
                $replies = $conn->query("select * from replies where post_idx = " . $idx . " and parent_idx IS null order by idx")->fetchAll();
                if ($replies) {
                    foreach ($replies as $reply) {
                        ?>

                        <!-- 댓글 섹션 예시 -->
                        <div class="mt-4 card">
                            <div class="card-body">
                                <input type="hidden" class="reply-idx" value="<?= $reply['idx'] ?>"/>
                                <div class="media-body mb-3">
                                    <h5 class="mt-0"><?= $reply['name'] ?></h5>
                                    <p class="mb-0">작성일: <?= $reply['created_at'] ?></p>
                                    <?= nl2br($reply['content']) ?>
                                </div>
                                <button class="btn btn-primary btn-reply-edit" data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                    수정
                                </button>
                                <button class="btn btn-primary btn-reply-delete" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                    삭제
                                </button>
                                <button class="btn btn-primary btn-sub-reply">
                                    대댓글
                                </button>
                            </div>
                        </div>

                        <?php
                        $subReplies = $conn->query("select * from replies where parent_idx = " . $reply['idx'] . " order by idx")->fetchAll();
                        if ($subReplies) {
                            foreach ($subReplies as $subReply) {
                                ?>
                                <div class="mt-4 card ml-4">
                                    <div class="card-body">
                                        <input type="hidden" class="reply-idx" value="<?= $subReply['idx'] ?>"/>
                                        <div class="media-body mb-3">
                                            <h5 class="mt-0"><?= $subReply['name'] ?></h5>
                                            <p class="mb-0">작성일: <?= $subReply['created_at'] ?></p>
                                            <?= nl2br($subReply['content']) ?>
                                        </div>
                                        <button class="btn btn-primary btn-reply-edit" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                            수정
                                        </button>
                                        <button class="btn btn-primary btn-reply-delete" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                            삭제
                                        </button>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                    include_once "part/editModal.php";
                    include_once "part/deleteModal.php";
                }
            }
        } else {
            echo "<script>alert('존재하지 않는 게시물입니다.');history.back();</script>";
        }
        ?>
    </div>
    <script src="/bbs/assets/script/read.js"></script>
</body>
</html>