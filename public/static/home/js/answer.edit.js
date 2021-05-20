layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var $tips = $('#tips');

    if ($tips.length > 0) {
        layer.open({
            type: 2,
            title: '答题指南',
            content: $tips.data('url'),
            area: ['600px', '320px'],
        });
    }

});