<!doctype html>
<?php

use db\connection;

include "part/header.php";
?>
<body>

<?php
require_once("../db/connection.php");
$conn = new connection();
$conn = $conn->getConnection();
$stmt = $conn->prepare("select * from posts where idx = :idx");
$stmt->bindParam('idx', $_GET['idx']);
$post = $stmt->execute();
$post = $stmt->fetch();
if ($post){
    ?>
<div class="m-4">
    <div class="container mt-5">
        <h3 class="d-inline"><a href="/bbs/view">자유게시판</a></h3>/<h4 class="d-inline">글 읽기</h4>
        <p class="mt-1 mb-3">글의 상세 내용입니다.</p>
        <hr/>
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

        <a href="/bbs/view/modify.php?idx=<?=$post['idx']?>" class="btn btn-primary">수정하기</a>
        <a href="" class="btn btn-dark">삭제하기</a>
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

    </div>
    <?php
    } else {
        echo "<script>alert('존재하지 않는 게시물입니다.');history.back();</script>";
    }
    ?>
</body>
</html>