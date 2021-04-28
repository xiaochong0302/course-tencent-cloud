layui.use(['jquery', 'form', 'layer', 'upload'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;
    var upload = layui.upload;

    form.on('select(source_type)', function (data) {
        var block = $('#source-url-block');
        if (data.value === '1') {
            block.hide();
        } else {
            block.show();
        }
    });

    var xmTags = JSON.parse($('input[name=xm_tags]').val());

    xmSelect.render({
        el: '#xm-tag-ids',
        name: 'xm_tag_ids',
        max: 3,
        data: xmTags,
        filterable: true,
        filterMethod: function (val, item, index, prop) {
            return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
        }
    });

    upload.render({
        elem: '#change-cover',
        url: '/upload/cover/img',
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