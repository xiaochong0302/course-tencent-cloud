layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    /**
     * 收藏
     */
    $('.icon-star').on('click', function () {
        var $this = $(this);
        var $parent = $this.parent();
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $parent.data('url'),
                success: function () {
                    if ($this.hasClass('layui-icon-star-fill')) {
                        $this.removeClass('layui-icon-star-fill');
                        $this.addClass('layui-icon-star');
                        $parent.attr('title', '收藏');
                    } else {
                        $this.removeClass('layui-icon-star');
                        $this.addClass('layui-icon-star-fill');
                        $parent.attr('title', '取消收藏');
                    }
                }
            });
        });
    });

    /**
     * 咨询
     */
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

    /**
     * 打赏
     */
    $('.btn-reward').on('click', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    /**
     * 购买（课程|套餐)
     */
    $('body').on('click', '.btn-buy', function () {
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    /**
     * 浏览章节
     */
    $('body').on('click', '.view-lesson', function () {
        if ($(this).hasClass('deny')) {
            return false;
        }
        var url = $(this).data('url');
        helper.checkLogin(function () {
            window.location.href = url;
        });
    });

    /**
     * 咨询详情
     */
    $('body').on('click', '.consult-details', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '咨询详情',
            content: [url, 'no'],
            area: ['720px', '320px']
        });
    });

    /**
     * 点赞（咨询|评价）
     */
    $('body').on('click', '.icon-praise', function () {
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

    if ($('#tab-packages').length > 0) {
        var $tabPackages = $('#tab-packages');
        helper.ajaxLoadHtml($tabPackages.data('url'), $tabPackages.attr('id'));
    }

    if ($('#tab-consults').length > 0) {
        var $tabConsults = $('#tab-consults');
        helper.ajaxLoadHtml($tabConsults.data('url'), $tabConsults.attr('id'));
    }

    if ($('#tab-reviews').length > 0) {
        var $tabReviews = $('#tab-reviews');
        helper.ajaxLoadHtml($tabReviews.data('url'), $tabReviews.attr('id'));
    }

    if ($('#sidebar-topics').length > 0) {
        var $sdTopics = $('#sidebar-topics');
        helper.ajaxLoadHtml($sdTopics.data('url'), $sdTopics.attr('id'));
    }

    if ($('#sidebar-recommended').length > 0) {
        var $sdRecommended = $('#sidebar-recommended');
        helper.ajaxLoadHtml($sdRecommended.data('url'), $sdRecommended.attr('id'));
    }

    if ($('#sidebar-related').length > 0) {
        var $sdRelated = $('#sidebar-related');
        helper.ajaxLoadHtml($sdRelated.data('url'), $sdRelated.attr('id'));
    }

});