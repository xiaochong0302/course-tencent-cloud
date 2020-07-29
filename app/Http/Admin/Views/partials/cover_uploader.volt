<script>

    layui.use(['jquery', 'layer', 'upload'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;
        var upload = layui.upload;

        upload.render({
            elem: '#change-cover',
            url: '/admin/upload/img/cover',
            accept: 'images',
            acceptMime: 'image/*',
            before: function () {
                layer.load();
            },
            done: function (res, index, upload) {
                $('#img-cover').attr('src', res.data.src);
                $('input[name=cover]').val(res.data.src);
                layer.closeAll('loading');
            },
            error: function (index, upload) {
                layer.msg('上传文件失败', {icon: 2});
            }
        });

    });

</script>