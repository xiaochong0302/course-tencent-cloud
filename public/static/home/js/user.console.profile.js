layui.use(['jquery', 'upload', 'layer', 'layarea'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var upload = layui.upload;
    var layarea = layui.layarea;

    upload.render({
        elem: '#change-avatar',
        url: '/upload/avatar/img',
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
        done: function (res) {
            $('#img-avatar').attr('src', res.data.url);
            $('input[name=avatar]').val(res.data.url);
            layer.closeAll('loading');
        },
        error: function () {
            layer.msg('上传文件失败', {icon: 2});
        }
    });

    layarea.render({
        elem: '#area-picker',
        change: function (res) {
            console.log(res);
        }
    });

});
