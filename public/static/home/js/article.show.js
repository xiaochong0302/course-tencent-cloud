layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    var $sidebarRelated = $('#sidebar-related');
    var $commentList = $('#comment-list');

    if ($sidebarRelated.length > 0) {
        helper.ajaxLoadHtml($sidebarRelated.data('url'), $sidebarRelated.attr('id'));
    }

    if ($commentList.length > 0) {
        helper.ajaxLoadHtml($commentList.data('url'), $commentList.attr('id'));
    }

    $('.article-edit').on('click', function () {
        window.location.href = $(this).data('url');
    });

    $('.article-close').on('click', function () {
        var url = $(this).data('url');
        $.post(url, function (res) {
            if (res.msg) {
                layer.msg(res.msg, {icon: 1});
            }
            setTimeout(function () {
                window.location.reload()
            }, 1500);
        });
    });

    $('.icon-star').on('click', function () {
        var $this = $(this);
        var $parent = $this.parent();
        var $favoriteCount = $parent.next();
        var favoriteCount = $favoriteCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $parent.data('url'),
                success: function () {
                    if ($this.hasClass('layui-icon-star-fill')) {
                        $this.removeClass('layui-icon-star-fill');
                        $this.addClass('layui-icon-star');
                        $parent.attr('title', '收藏文章');
                        favoriteCount--;
                    } else {
                        $this.removeClass('layui-icon-star');
                        $this.addClass('layui-icon-star-fill');
                        $parent.attr('title', '取消收藏');
                        favoriteCount++;
                    }
                    $favoriteCount.data('count', favoriteCount).text(favoriteCount);
                }
            });
        });
    });

    $('.icon-praise').on('click', function () {
        var $this = $(this);
        var $parent = $this.parent();
        var $likeCount = $parent.next();
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

    $('.icon-reply').on('click', function () {
        $('html').scrollTop($('#comment-anchor').offset().top);
    });

});