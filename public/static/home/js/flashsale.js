layui.use(['jquery', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var helper = layui.helper;

    setInterval(function () {
        window.location.reload();
    }, 60000);

    $('.package-link').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '套餐课程',
            content: url,
            area: ['800px', '280px']
        });
    });

    $('.order').on('click', function () {
        var id = $(this).data('id');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: '/flash/sale/order',
                data: {id: id},
                success: function (res) {
                    window.location.href = res.location;
                }
            });
        });
    });

});