layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    $('body').on('click', '.answer-report', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: url,
                success: function () {

                }
            });
        });
    });

    $('body').on('click', '.answer-like', function () {
        var $this = $(this);
        var $likeCount = $this.prev();
        var likeCount = $likeCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $this.data('url'),
                success: function () {
                    if ($this.hasClass('liked')) {
                        $this.attr('title', '点赞支持').text('点赞').removeClass('liked');
                        likeCount--;
                    } else {
                        $this.attr('title', '取消点赞').text('已赞').addClass('liked');
                        likeCount++;
                    }
                    $likeCount.data('count', likeCount).text(likeCount);
                }
            });
        });
    });

    $('body').on('click', '.answer-edit', function () {
        window.location.href = $(this).data('url');
    });

});