layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.btn-redeem').on('click', function () {
        var url = $(this).data('url');
        var data = {gift_id: $(this).data('id')};
        layer.confirm('兑换不支持退换，确定要兑换该物品吗？', function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                success: function (res) {
                    layer.msg(res.msg, {icon: 1});
                    setTimeout(function () {
                        window.location.href = '/uc/redeems';
                    }, 3000);
                }
            });
        });
    });

});