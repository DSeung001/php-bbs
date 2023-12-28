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

    // 댓글 수정 모달에 데이터 세팅 기능
    $(".btn-reply-edit").click(function () {
        let replyIdx = $(this).parent().parent().find(".reply-idx").val();
        console.log(replyIdx);
        $("#editModal .modal-reply-idx").val(replyIdx);
        $.ajax({
            url: "/bbs/reply/read",
            type: "GET",
            data: {
                reply_idx: replyIdx
            },
            success: function (data) {
                console.log(data);
                if (data.result) {
                    $("#editModalName").text(data.data.name);
                    $("#editModalPw").val("")
                    $("#editModalContent").val(data.data.content);
                } else {
                    alert(data.msg);
                }
            },
            error: function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })

    // 댓글 삭제 모달에 데이터 세팅 기능
    $(".btn-reply-delete").click(function () {
        let replyIdx = $(this).parent().parent().find(".reply-idx").val();
        $("#deleteModal .modal-reply-idx").val(replyIdx);
        $.ajax({
            url: "/bbs/reply/read",
            type: "GET",
            data: {
                reply_idx: replyIdx
            },
            success: function (data) {
                if (data.result) {
                    $("#deleteModalName").text(data.data.name);
                    $("#deleteModalPw").val("")
                    $("#deleteModalContent").text(data.data.content);
                } else {
                    alert(data.msg);
                }
            },
            error: function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })

    // 댓글 수정 ajax 기능
    $("#editModalSubmit").click(function (){
        $.ajax({
            url: "/bbs/reply/update",
            type: "POST",
            data: {
                reply_idx: $("#editModal .modal-reply-idx").val(),
                pw: $("#editModalPw").val(),
                content: $("#editModalContent").val()
            },
            success: function (data) {
                console.log(data);
                if (data.result) {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error : function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })

    // 댓글 삭제 ajax 기능
    $("#deleteModalSubmit").click(function (){
        $.ajax({
            url: "/bbs/reply/delete",
            type: "POST",
            data: {
                reply_idx: $("#deleteModal .modal-reply-idx").val(),
                pw: $("#deleteModalPw").val()
            },
            success: function (data) {
                console.log(data);
                if (data.result) {
                    alert(data.msg);
                    location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error : function (e) {
                alert("에러 발생 : " + e.responseText);
            }
        })
    })
});