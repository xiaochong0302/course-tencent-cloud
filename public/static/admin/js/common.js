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
            xhr.setRequestHeader('X-Csrf-Token', $('meta[name="csrf-token"]').attr('content'));
        }
    });

    form.on('submit(go)', function (data) {
        var submit = $(this);
        submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                var icon = res.code === 0 ? 1 : 2;
                if (res.msg) {
                    layer.msg(res.msg, {icon: icon});
                }
                if (res.location) {
                    setTimeout(function () {
                        window.location.href = res.location;
                    }, 1500);
                } else {
                    submit.removeAttr('disabled').removeClass('layui-btn-disabled');
                }
            },
            error: function (xhr) {
                var json = JSON.parse(xhr.responseText);
                layer.msg(json.msg, {icon: 2});
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            }
        });
        return false;
    });

    form.on('switch(published)', function (data) {
        var checked = $(this).is(':checked');
        var published = checked ? 1 : 0;
        var url = $(this).attr('data-url');
        var tips = published === 1 ? '确定要上线？' : '确定要下线？';
        layer.confirm(tips, function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: {published: published},
                success: function (res) {
                    layer.msg(res.msg, {icon: 1});
                },
                error: function (xhr) {
                    var json = JSON.parse(xhr.responseText);
                    layer.msg(json.msg, {icon: 2});
                    data.elem.checked = !checked;
                    form.render();
                }
            });
        }, function () {
            data.elem.checked = !checked;
            form.render();
        });
    });

    $('.kg-priority-input').on('change', function () {
        var priority = $(this).val();
        var url = $(this).attr('data-url');
        $.ajax({
            type: 'POST',
            url: url,
            data: {priority: priority},
            success: function (res) {
                layer.msg(res.msg, {icon: 1});
            },
            error: function (xhr) {
                var json = JSON.parse(xhr.responseText);
                layer.msg(json.msg, {icon: 2});
            }
        });
    });

    $('.kg-delete,.kg-restore').on('click', function () {
        var url = $(this).attr('data-url');
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