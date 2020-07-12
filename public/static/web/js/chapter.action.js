layui.use(['jquery', 'form', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    var $likeIcon = $('#icon-like');
    var $likeCount = $('#like-count');
    var likeCount = parseInt($likeCount.text());

    $likeIcon.on('click', function () {
        $.ajax({
            type: 'POST',
            url: $(this).data('url'),
            success: function (res) {
                if ($likeIcon.hasClass('active')) {
                    $likeIcon.removeClass('active');
                    $likeCount.text(likeCount - 1);
                    likeCount -= 1;
                } else {
                    $likeIcon.addClass('active');
                    $likeCount.text(likeCount + 1);
                    likeCount += 1;

                }
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2});
            }
        });
    });

    $('#icon-share').on('click', function () {

    });

});