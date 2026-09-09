layui.use(['jquery', 'layer', 'upload'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;

    upload.render({
        elem: '#change-avatar',
        url: '/admin/upload/avatar/img',
        accept: 'images',
        acceptMime: 'image/*',
        before: function () {
            layer.load();
        },
        done: function (res, index, upload) {
            $('#img-avatar').attr('src', res.data.url);
            $('input[name=avatar]').val(res.data.url);
            layer.closeAll('loading');
        },
        error: function (index, upload) {
            layer.msg('上传文件失败', {icon: 2});
        }
    });

});
