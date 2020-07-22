layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    /**
     * 订单详情
     */
    $('.btn-order-info').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '订单详情',
            content: [url, 'no'],
            area: '800px',
            offset: '200px'
        });
    });

    /**
     * 退款详情
     */
    $('.btn-refund-info').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '退款详情',
            content: [url, 'no'],
            area: '800px',
            offset: '200px'
        });
    });

    if ($('#tab-courses').length > 0) {
        var $tabCourses = $('#tab-courses');
        helper.ajaxLoadHtml($tabCourses.data('url'), $tabCourses.attr('id'));
    }

    if ($('#tab-users').length > 0) {
        var $tabUsers = $('#tab-users');
        helper.ajaxLoadHtml($tabUsers.data('url'), $tabUsers.attr('id'));
    }

});