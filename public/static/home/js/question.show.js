layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    var $answerList = $('#answer-list');
    var $answerInfo = $('#answer-info');
    var $sidebarRelated = $('#sidebar-related');

    if ($answerList.length > 0) {
        helper.ajaxLoadHtml($answerList.data('url'), $answerList.attr('id'));
    }

    if ($answerInfo.length > 0) {
        helper.ajaxLoadHtml($answerInfo.data('url'), $answerInfo.attr('id'));
    }

    if ($sidebarRelated.length > 0) {
        helper.ajaxLoadHtml($sidebarRelated.data('url'), $sidebarRelated.attr('id'));
    }

    $('.layui-tab-title > li').on('click', function () {
        helper.ajaxLoadHtml($(this).data('url'), $answerList.attr('id'));
    });

    $('.question-edit').on('click', function () {
        window.location.href = $(this).data('url');
    });

    $('.btn-answer').on('click', function () {
        window.location.href = $(this).data('url');
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
                        $parent.attr('title', '收藏问题');
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
        $('html').scrollTop($('#answer-anchor').offset().top);
    });

});