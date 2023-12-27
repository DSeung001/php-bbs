$(document).ready(function () {
    // 추천 기능
    $("#thumbsUp").click(function () {
        $.ajax({
            url: "/bbs/post/thumbsUp",
            type: "POST",
            data: {
                post_idx: $("#postIdx").val()
            },
            success: function (data) {
                alert(data.msg);
                if (data.result) {
                    location.reload();
                }
            },
            error: function (e) {
                alert("에러 발생");
            }
        });
    });
});