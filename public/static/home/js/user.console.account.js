layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.btn-edit-pwd').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '修改密码',
            content: [url, 'no'],
            area: ['640px', '320px'],
            cancel: function () {
                window.location.reload();
            }
        });
    });

    $('.btn-edit-phone').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '修改手机',
            content: [url, 'no'],
            area: ['640px', '420px'],
            cancel: function () {
                window.location.reload();
            }
        });
    });

    $('.btn-edit-email').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '修改邮箱',
            content: [url, 'no'],
            area: ['640px', '420px'],
            cancel: function () {
                window.location.reload();
            }
        });
    });

});