layui.config({
    base: '/static/lib/layui/extends/'
}).extend({
    dropdown: 'dropdown'
});

layui.use(['jquery', 'form', 'element', 'layer', 'dropdown'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;

    $.ajaxSetup({
        beforeSend: function (xhr) {
            var csrfTokenKey = $('meta[name="csrf-token-key"]').attr('content');
            var csrfTokenValue = $('meta[name="csrf-token-value"]').attr('content');
            xhr.setRequestHeader('X-Csrf-Token-Key', csrfTokenKey);
            xhr.setRequestHeader('X-Csrf-Token-Value', csrfTokenValue);
        }
    });

    form.on('submit(go)', function (data) {
        var submit = $(this);
        submit.attr('disabled', true).text('提交中...');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                if (res.msg != '') {
                    var icon = (res.code == 0) ? 1 : 2;
                    layer.msg(res.msg, {icon: icon});
                }
                if (res.location) {
                    setTimeout(function () {
                        window.location.href = res.location;
                    }, 1500);
                }
                submit.attr('disabled', false).text('提交');
            },
            error: function (xhr) {
                var json = JSON.parse(xhr.responseText);
                layer.msg(json.msg, {icon: 2});
                submit.attr('disabled', false).text('提交');
            }
        });
        return false;
    });

    $('.kg-delete,.kg-restore').on('click', function () {
        var url = $(this).attr('url');
        var tips = $(this).hasClass('kg-delete') ? '确定要删除吗？' : '确定要还原吗？';
        layer.confirm(tips, function () {
            $.ajax({
                type: 'POST',
                url: url,
                success: function (res) {
                    layer.msg(res.msg, {icon: 1});
                    if (res.location) {
                        setTimeout(function () {
                            window.location.href = res.location;
                        }, 1500);
                    } else {
                        window.location.reload();
                    }
                },
                error: function (xhr) {
                    var json = JSON.parse(xhr.responseText);
                    layer.msg(json.msg, {icon: 2});
                }
            });
        });
    });

    $('.kg-back').on('click', function () {
        window.history.back();
    });

});