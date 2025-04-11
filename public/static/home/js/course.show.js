layui.use(['jquery', 'layer', 'rate', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var rate = layui.rate;
    var helper = layui.helper;

    rate.render({
        elem: '#rating1',
        value: $('#rating1').data('value'),
        readonly: true,
        half: true,
    });

    rate.render({
        elem: '#rating2',
        value: $('#rating2').data('value'),
        readonly: true,
        half: true,
    });

    rate.render({
        elem: '#rating3',
        value: $('#rating3').data('value'),
        readonly: true,
        half: true,
    });

    /**
     * 收藏
     */
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
                        $parent.attr('title', '收藏课程');
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
     * 浏览章节
     */
    $('.lesson-item').on('click', function () {
        if ($(this).hasClass('deny')) {
            return false;
        }
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
    $('body').on('click', '.action-like', function () {
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

    if ($('#tab-resources').length > 0) {
        var $tabResources = $('#tab-resources');
        helper.ajaxLoadHtml($tabResources.data('url'), $tabResources.attr('id'));
    }

    if ($('#sidebar-topics').length > 0) {
        var $sdTopics = $('#sidebar-topics');
        helper.ajaxLoadHtml($sdTopics.data('url'), $sdTopics.attr('id'));
    }

    if ($('#sidebar-related').length > 0) {
        var $sdRelated = $('#sidebar-related');
        helper.ajaxLoadHtml($sdRelated.data('url'), $sdRelated.attr('id'));
    }

});
