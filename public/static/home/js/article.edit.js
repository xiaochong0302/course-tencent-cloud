layui.use(['jquery', 'form'], function () {

    var $ = layui.jquery;
    var form = layui.form;

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
        autoRow: true,
        filterable: true,
        filterMethod: function (val, item, index, prop) {
            return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
        }
    });

});