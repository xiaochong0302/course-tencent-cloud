layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    /**
     * 查看咨询
     */
    $('.btn-show-consult').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '咨询详情',
            content: url,
            area: ['720px', '480px']
        });
    });

    /**
     * 编辑咨询
     */
    $('.btn-edit-consult').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '编辑咨询',
            content: [url, 'no'],
            area: ['720px', '400px'],
            cancel: function () {
                parent.location.reload();
            }
        });
    });

    /**
     * 发布评价
     */
    $('.btn-add-review').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '发布评价',
            content: [url, 'no'],
            area: ['640px', '400px'],
            cancel: function () {
                parent.location.reload();
            }
        });
    });

    /**
     * 修改评价
     */
    $('.btn-edit-review').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '修改评价',
            content: [url, 'no'],
            area: ['640px', '400px'],
            cancel: function () {
                parent.location.reload();
            }
        });
    });

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

    /**
     * 群组管理
     */
    $('.btn-manage-group').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '群组管理',
            maxmin: true,
            resize: false,
            content: [url, 'no'],
            area: ['1000px', '510px']
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