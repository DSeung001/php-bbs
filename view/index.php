<?php
$conn = require_once $_SERVER['DOCUMENT_ROOT'] . "/bbs/db/connection.php";

?>
<!doctype html>
<head>
    <meta charset="UTF-8">
    <title>게시판</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>
<body>
<div id="board_area" class="m-4">
    <h3>자유게시판</h3>

    <div id="write_btn" class="mb-4">
        <p class="d-inline">자유롭게 글을 쓸 수 있는 게시판입니다.</p>

        <a href="/page/board/write.php">
            <button class="btn btn-primary float-right">글쓰기</button>
        </a>
    </div>

    <!-- 게시물 목록 테이블 -->
    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <th width="70">번호</th>
            <th width="500">제목</th>
            <th width="120">글쓴이</th>
            <th width="100">작성일</th>
            <th width="100">추천수</th>
            <th width="100">조회수</th>
        </tr>
        </thead>
        <tbody>
        <!-- 게시물 아이템 -->

        <?php
        // board테이블에서 idx를 기준으로 내림차순해서 10개까지 표시
        $list = $conn->query("select * from board order by idx desc limit 0,10")->fetchAll();
        if ($list) {
            foreach ($list as $item) {
                //title변수에 DB에서 가져온 title을 선택
                $title = $item["title"];
                if (strlen($title) > 30) {
                    //title이 30을 넘어서면 ...표시
                    $title = str_replace($item["title"], mb_substr($item["title"], 0, 30, "utf-8") . "...", $item["title"]);
                }
                ?>

                <tr>
                    <td width="70"><?php echo $item['idx']; ?></td>
                    <td width="500"><a href=""><?php echo $title; ?></a></td>
                    <td width="120"><?php echo $item['name'] ?></td>
                    <td width="100"><?php echo $item['date'] ?></td>
                    <td width="100"><?php echo $item['hit']; ?></td>
                    <!-- 추천수 표시해주기 위해 추가한 부분 -->
                    <td width="100"><?php echo $item['thumbup'] ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6' align='center'>게시글이 없습니다.</td></tr>";
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