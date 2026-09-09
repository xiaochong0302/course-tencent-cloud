layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.kg-preview').on('click', function () {

        var playUrl = $(this).data('play-url') || '';
        var fileId = $(this).data('file-id') || 0;
        var encrypt = $(this).data('encrypt') || 0;

        var frameUrl = '/admin/vod/player?play_url=' + playUrl + '&file_id=' + fileId + '&encrypt=' + encrypt;

        layer.open({
            id: 'player',
            type: 2,
            title: '媒体播放器',
            resize: false,
            area: ['720px', '456px'],
            content: [frameUrl, 'no'],
        });

    });

});