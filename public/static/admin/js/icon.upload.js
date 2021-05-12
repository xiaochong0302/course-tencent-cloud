layui.use(['jquery', 'layer', 'upload'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;

    upload.render({
        elem: '#change-icon',
        url: '/admin/upload/icon/img',
        accept: 'images',
        acceptMime: 'image/*',
        before: function () {
            layer.load();
        },
        done: function (res, index, upload) {
            $('#img-icon').attr('src', res.data.src);
            $('input[name=icon]').val(res.data.src);
            layer.closeAll('loading');
        },
        error: function (index, upload) {
            layer.msg('上传文件失败', {icon: 2});
        }
    });

});