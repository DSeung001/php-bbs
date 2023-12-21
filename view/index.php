<?php
use DB\Connection;

$conn = new Connection();
$conn = $conn->getConnection();
?>
<!doctype html>
<?php
include "part/header.php";
?>
<body>
<div class="m-4">
    <h3><a href="/bbs">자유게시판</a></h3>

    <div id="write_btn" class="mb-4">
        <p class="d-inline">자유롭게 글을 쓸 수 있는 게시판입니다.</p>

        <a href="./create">
            <button class="btn btn-primary float-right">글쓰기</button>
        </a>
    </div>

    <!-- 게시물 목록 테이블 -->
    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <th width="80">번호</th>
            <th width="300">제목</th>
            <th width="100">글쓴이</th>
            <th width="80">추천수</th>
            <th width="80">조회수</th>
            <th width="100">작성일</th>
        </tr>
        </thead>
        <tbody>
        <!-- 게시물 아이템 -->

        <?php
        // board테이블에서 idx를 기준으로 내림차순해서 10개까지 표시
        $posts = $conn->query("select * from posts order by idx desc limit 0,10")->fetchAll();
        if ($posts) {
            foreach ($posts as $post) {
                //title변수에 DB에서 가져온 title을 선택
                $title = $post["title"];
                if (strlen($title) > 30) {
                    //title이 30을 넘어서면 ...표시
                    $title = str_replace($post["title"], mb_substr($post["title"], 0, 30, "utf-8") . "...", $post["title"]);
                }
                ?>

                <tr>
                    <td><?= $post['idx']; ?></td>
                    <td>
                        <a href="./read?idx=<?= $post['idx']?>">
                            <?= $title; ?>
                        </a>
                    </td>
                    <td><?= $post['name'] ?></td>
                    <td><?= $post['thumbs_up'] ?></td>
                    <td><?= $post['hit'] ?></td>
                    <td><?= $post['created_at'] ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>게시글이 없습니다.</td></tr>";
        }
        ?>
        <!-- 추가적인 게시물 아이템들을 여기에 추가 -->
        </tbody>
    </table>

    <!-- 페이지네이션 -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <!--            <li class="page-item"><a class="page-link" href="#">2</a></li>-->
            <!--            <li class="page-item"><a class="page-link" href="#">3</a></li>-->
            <li class="page-item">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

</div>
</body>
</html>