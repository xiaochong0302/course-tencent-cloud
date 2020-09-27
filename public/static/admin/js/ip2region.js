layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.kg-ip2region').on('click', function () {
        var ip = $(this).data('ip');
        var url = '/admin/ip2region?ip=' + ip;
        layer.open({
            type: 2,
            title: '地理位置',
            resize: false,
            area: ['640px', '180px'],
            content: [url, 'no']
        });
    });

});