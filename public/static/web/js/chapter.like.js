layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    $('.icon-praise').on('click', function () {
        var $this = $(this);
        var $parent = $this.parent();
        var $likeCount = $this.next();
        var likeCount = parseInt($likeCount.text());
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $parent.data('url'),
                success: function () {
                    if ($this.hasClass('active')) {
                        $this.removeClass('active');
                        $parent.attr('title', '点赞');
                        $likeCount.text(likeCount - 1);
                        likeCount -= 1;
                    } else {
                        $this.addClass('active');
                        $parent.attr('title', '取消点赞');
                        $likeCount.text(likeCount + 1);
                        likeCount += 1;
                    }
                }
            });
        });
    });

});