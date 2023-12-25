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

    <!--검색-->
    <div class="container mt-4 mb-3">
        <form action="" method="get">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="form-inline full-width-form">
                        <div class="form-group mb-2 flex-fill">
                            <label for="searchInput" class="sr-only">검색</label>
                            <input name="search" type="text" class="form-control w-100" id="searchInput" placeholder="Search"
                                   value="<?= $_GET['search'] ?? '' ?>">
                        </div>
                        <button id="searchSubmit" type="submit" class="btn btn-primary mb-2">검색</button>
                    </div>
                </div>
            </div>
        </form>
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
        <?php
        // 현재 페이지
        $currentPage = $_GET['page'] ?? 1;
        $perPage = 5;
        $startPage = ($currentPage - 1) * $perPage;

        // 전체 게시글 수
        $totalStmt = $conn->prepare("select count(idx) from posts where title like :search");
        $totalStmt->bindValue('search', '%' . ($_GET['search'] ?? '') . '%');
        $totalStmt->execute();
        $total = $totalStmt->fetchColumn();

        // 전체 페이지 및 현재 보여줄 마지막 페이지
        $totalPage = ceil($total / $perPage);
        $endPage = $totalPage > $currentPage + 4 ? $currentPage + 4 : $totalPage;

        // 게시글 목록 가져오기
        $dataStmt = $conn->prepare("select p.*,
        (SELECT COUNT(*) FROM replies r WHERE r.post_idx = p.idx) AS reply_count,
        CASE WHEN TIMESTAMPDIFF(MINUTE, p.created_at, NOW()) <= 1440 THEN 1
        ELSE 0 END AS is_new
        from posts p
        where p.title like :search
        order by idx desc limit :start, :perPage");
        $dataStmt->bindValue('search', '%' . ($_GET['search'] ?? '') . '%');
        $dataStmt->bindParam('start', $startPage, PDO::PARAM_INT);
        $dataStmt->bindParam('perPage', $perPage, PDO::PARAM_INT);
        $dataStmt->execute();
        $posts = $dataStmt->fetchAll();

        if ($posts) {
            $bonusIdx = 0;
            foreach ($posts as $post) {
                /// 30 글자 초과시 ... 저리
                $title = $post["title"];
                if (strlen($title) > 30) {
                    $title = str_replace($post["title"], mb_substr($post["title"], 0, 30, "utf-8") . "...", $post["title"]);
                }
                ?>

                <tr>
                    <td><?= $total - ($startPage + $bonusIdx++) ?></td>
                    <td>
                        <a href="./read?idx=<?= $post['idx'] ?>">
                            <?= $title . " [" . $post['reply_count'] . "]"; ?>
                            <?php if ($post['is_new']) { ?>
                                <span class="badge badge-primary">new</span>
                            <?php } ?>
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
        </tbody>
    </table>

    <!-- 페이지네이션 -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="?page=1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php
            for ($page = max($currentPage - 4, 1); $page <= $endPage; $page++) {
                $isActive = $page == $currentPage ? 'active' : '';
                echo "<li class='page-item $isActive'><span class='page-link' data-page='$page'>$page</span></li>";
            }
            ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $totalPage ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
</body>
<script src="/bbs/assets/script/index.js"></script>
</html>