layui.use(['jquery', 'form', 'layer'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;

    $('.publish').on('click', function () {
        var layerWidth = 360;
        var layerTop = $(this).offset().top + $(this).height() + 5 + 'px';
        var layerLeft = ($(this).offset().left + $(this).width() - layerWidth) + 'px';
        layer.open({
            type: 1,
            title: false,
            closeBtn: false,
            shadeClose: true,
            content: $('#layer-publish'),
            offset: [layerTop, layerLeft],
            area: layerWidth + 'px',
        });
    });

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
        layVerify: 'required',
        layVerType: 'msg',
        autoRow: true,
        filterable: true,
        filterMethod: function (val, item, index, prop) {
            return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
        }
    });

});