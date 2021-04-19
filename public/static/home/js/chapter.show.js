layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $commentList = $('#comment-list');

    if ($commentList.length > 0) {
        helper.ajaxLoadHtml($commentList.data('url'), $commentList.attr('id'));
    }

    $('.icon-praise').on('click', function () {
        var $this = $(this);
        var $parent = $this.parent();
        var $likeCount = $parent.next();
        var likeCount = $likeCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $parent.data('url'),
                success: function () {
                    if ($this.hasClass('active')) {
                        $this.removeClass('active');
                        $parent.attr('title', '点赞支持');
                        likeCount--;
                    } else {
                        $this.addClass('active');
                        $parent.attr('title', '取消点赞');
                        likeCount++;
                    }
                    $likeCount.data('count', likeCount).text(likeCount);
                }
            });
        });
    });

    $('.icon-help').on('click', function () {
        var url = $(this).parent().data('url');
        helper.checkLogin(function () {
            layer.open({
                type: 2,
                title: '课程咨询',
                content: [url, 'no'],
                area: ['640px', '300px']
            });
        });
    });

    $('.icon-download').on('click', function () {
        var url = $(this).parent().data('url');
        helper.checkLogin(function () {
            layer.open({
                type: 2,
                title: '资料下载',
                content: [url, 'no'],
                area: ['640px', '300px']
            });
        });
    });

});