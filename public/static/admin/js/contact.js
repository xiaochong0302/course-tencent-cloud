layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.kg-contact').on('click', function () {
        var content = '<div class="kg-layer-content">' +
            '<table class="layui-table">' +
            '<tr><td>联系人</td><td>手机号</td><td>收货地址</td></tr>' +
            '<tr><td>' + $(this).data('name') + '</td><td>' + $(this).data('phone') + '</td><td>' + $(this).data('address') + '</td></tr>' +
            '</table>' +
            '</div>';
        layer.open({
            type: 1,
            title: '联系信息',
            area: ['800px', '160px'],
            content: content
        });
    });

});