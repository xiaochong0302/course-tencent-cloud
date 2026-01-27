layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

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

    $('.sidebar-lesson').on('click', function () {
        if ($(this).hasClass('deny')) {
            return false;
        }
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    $('.icon-reply').on('click', function () {
        $('html').scrollTop($('#comment-anchor').offset().top);
    });

    var $container = $('.sidebar-chapter-list');
    var chapterId = $('input[name="chapter.id"]').val();
    var $target = $('li[data-url="/chapter/' + chapterId + '"]');

    if ($container.length > 0 && $target.length > 0) {
        var containerOffset = $container.offset().top;
        var targetOffset = $target.offset().top;
        var relativePosition = targetOffset - containerOffset - 100;
        $container.animate({scrollTop: relativePosition}, 600);
    }

    var $commentList = $('#comment-list');

    if ($commentList.length > 0) {
        helper.ajaxLoadHtml($commentList.data('url'), $commentList.attr('id'));
    }

});
