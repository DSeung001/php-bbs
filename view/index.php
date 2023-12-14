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
<div id="board_area">
    <h1>자유게시판</h1>
    <h4>자유롭게 글을 쓸 수 있는 게시판입니다.</h4>
    <table class="list-table">
        <thead>
        <tr>
            <th width="70">번호</th>
            <th width="500">제목</th>
            <th width="120">글쓴이</th>
            <th width="100">작성일</th>
            <!-- 추천수 항목 추가 -->
            <th width="100">추천수</th>
            <th width="100">조회수</th>
        </tr>
        </thead>
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
                <tbody>
                <tr>
                    <td width="70"><?php echo $item['idx']; ?></td>
                    <td width="500"><a href=""><?php echo $title; ?></a></td>
                    <td width="120"><?php echo $item['name'] ?></td>
                    <td width="100"><?php echo $item['date'] ?></td>
                    <td width="100"><?php echo $item['hit']; ?></td>
                    <!-- 추천수 표시해주기 위해 추가한 부분 -->
                    <td width="100"><?php echo $item['thumbup'] ?></td>
                </tr>
                </tbody>
                <?php
            }
        }else{
            echo "<tr><td colspan='5' align='center'>게시글이 없습니다.</td></tr>";
        }
        ?>
    </table>
    <div id="write_btn">
        <a href="/page/board/write.php">
            <button>글쓰기</button>
        </a>
    </div>
</div>
</body>
</html>