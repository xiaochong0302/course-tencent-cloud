layui.use(['jquery'], function () {

    var $ = layui.jquery;

    var xmTags = JSON.parse($('input[name=xm_tags]').val());

    xmSelect.render({
        el: '#xm-tag-ids',
        name: 'xm_tag_ids',
        max: 3,
        data: xmTags,
        autoRow: true,
        filterable: true,
        filterMethod: function (val, item) {
            return item.name.toLowerCase().indexOf(val.toLowerCase()) !== -1;
        }
    });

});
