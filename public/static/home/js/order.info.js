layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    var index = parent.layer.getFrameIndex(window.name);

    parent.layer.iframeAuto(index);

    $('.order-cancel').on('click', function () {
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

});