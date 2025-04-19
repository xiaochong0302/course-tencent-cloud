layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    /**
     * 查看咨询
     */
    $('.btn-show-consult').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '咨询详情',
            content: [url, 'no'],
            area: ['720px', '320px'],
        });
    });

    /**
     * 回复咨询
     */
    $('.btn-reply-consult').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '回复咨询',
            content: [url, 'no'],
            area: ['720px', '300px'],
        });
    });

    /**
     * 直播推流
     */
    $('.btn-live-push').on('click', function () {
        var url = $(this).data('url');
        layer.open({
            type: 2,
            title: '直播推流',
            content: [url, 'no'],
            area: ['640px', '420px'],
        });
    });

});
