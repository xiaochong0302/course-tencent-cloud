layui.use(['jquery', 'layer', 'upload'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;

    upload.render({
        elem: '#change-avatar',
        url: '/upload/img/avatar',
        accept: 'images',
        acceptMime: 'image/*',
        size: 512,
        auto: false,
        before: function () {
            layer.load();
        },
        choose: function (obj) {
            var flag = true;
            obj.preview(function (index, file, result) {
                console.log(file);
                var img = new Image();
                img.src = result;
                img.onload = function () {
                    if (img.width < 1000 && img.height < 1000) {
                        obj.upload(index, file);
                    } else {
                        flag = false;
                        layer.msg("图片尺寸必须小于 1000 * 1000");
                        return false;
                    }
                };
                return flag;
            });
        },
        done: function (res, index, upload) {
            $('#img-avatar').attr('src', res.data.src);
            $('input[name=avatar]').val(res.data.src);
            layer.closeAll('loading');
        },
        error: function (index, upload) {
            layer.msg('上传文件失败', {icon: 2});
        }
    });

});