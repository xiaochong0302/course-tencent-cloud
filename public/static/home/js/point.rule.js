layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.rule').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '积分规则',
            content: url,
            area: ['720px', '480px']
        });
    });

});