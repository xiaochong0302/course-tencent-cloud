layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    /**
     * 查看咨询
     */
    $('.btn-show-consult').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '咨询详情',
            content: [url, 'no'],
            area: ['720px', '320px'],
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
            area: ['720px', '420px'],
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
            area: ['640px', '480px'],
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
            area: ['640px', '480px'],
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
            offset: '200px',
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
            offset: '200px',
        });
    });

});
