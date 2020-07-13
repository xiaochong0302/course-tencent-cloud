layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    $('.rating-btn').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '课程评分',
            content: [url, 'no'],
            area: ['640px', '400px']
        });
    });

    $('body').on('click', '.like-icon', function () {
        var $this = $(this);
        var $likeCount = $this.next();
        var likeCount = parseInt($likeCount.text());
        $.ajax({
            type: 'POST',
            url: $this.data('url'),
            success: function (res) {
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