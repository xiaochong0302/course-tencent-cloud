layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    var course = {
        title: $('input[name="course.title"]').val(),
        cover: $('input[name="course.cover"]').val(),
        url: $('input[name="course.url"]').val(),
        qrcode: $('input[name="course.qrcode"]').val()
    };

    $('.rating-btn').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '课程评分',
            content: [url, 'no'],
            area: ['640px', '400px']
        });
    });

    $('.icon-heart').on('click', function () {
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: $this.parent().data('url'),
            beforeSend: function () {
                return helper.checkLogin();
            },
            success: function () {
                if ($this.hasClass('active')) {
                    $this.removeClass('active');
                } else {
                    $this.addClass('active');
                }
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2});
            }
        });
    });

    $('.icon-wechat').on('click', function () {
        var content = '<div class="qrcode"><img src="' + course.qrcode + '" alt="分享到微信"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content
        });
    });

    $('.icon-qq').on('click', function () {
        var title = '推荐一门好课：' + course.title + '，快来和我一起学习吧！';
        Share.qq(title, null, course.cover);
    });

    $('.icon-weibo').on('click', function () {
        var title = '推荐一门好课：' + course.title + '，快来和我一起学习吧！';
        Share.weibo(title, null, course.cover);
    });

    $('body').on('click', '.icon-praise', function () {
        var $this = $(this);
        var $likeCount = $this.next();
        var likeCount = parseInt($likeCount.text());
        $.ajax({
            type: 'POST',
            url: $this.data('url'),
            beforeSend: function () {
                return helper.checkLogin();
            },
            success: function () {
                if ($this.hasClass('active')) {
                    $this.removeClass('active');
                    $likeCount.text(likeCount - 1);
                    likeCount -= 1;
                } else {
                    $this.addClass('active');
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