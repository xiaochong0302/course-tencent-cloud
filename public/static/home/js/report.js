layui.use(['jquery', 'form', 'layer',], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;

    form.on('submit(report)', function (data) {
        var submit = $(this);
        submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function () {
                layer.msg('举报成功', {icon: 1});
                setTimeout(function () {
                    parent.layer.closeAll();
                }, 1500);
            },
            error: function () {
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            }
        });
        return false;
    });

});