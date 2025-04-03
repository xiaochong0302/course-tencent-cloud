layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var index = parent.layer.getFrameIndex(window.name);

    parent.layer.iframeAuto(index);

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
