layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    /**
     * 发布评价
     */
    $('.btn-review-add').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '发布评价',
            content: url,
            area: ['640px', '480px'],
        });
    });

    /**
     * 修改评价
     */
    $('.btn-review-edit').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '修改评价',
            content: url,
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
            content: url,
            area: ['800px', '420px'],
        });
    });

    /**
     * 支付订单
     */
    $('.btn-order-pay').on('click', function () {
        window.parent.location.href = $(this).data('url');
    });

    /**
     * 取消订单
     */
    $('.btn-order-cancel').on('click', function () {
        var url = $(this).data('url');
        var data = {sn: $(this).data('sn')};
        layer.confirm('确定要取消订单吗？', function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function () {
                    layer.msg('取消订单成功', {icon: 1});
                    parent.layer.close(index);
                    top.location.href = '/uc/orders';
                }
            });
        });
    });

    /**
     * 订单退款
     */
    $('.btn-order-refund').on('click', function () {
        var url = $(this).data('url');
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.close(index);
        parent.layer.open({
            type: 2,
            title: '申请退款',
            content: url,
            area: ['800px', '300px'],
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
            content: url,
            area: ['800px', '360px'],
        });
    });

    /**
     * 取消退款
     */
    $('.btn-refund-cancel').on('click', function () {
        var url = $(this).data('url');
        var data = {sn: $(this).data('sn')};
        layer.confirm('确定要取消退款吗？', function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function () {
                    layer.msg('取消退款成功', {icon: 1});
                    setTimeout(function () {
                        parent.window.location.href = '/uc/refunds';
                    }, 1500);
                }
            });
        });
    });

});
