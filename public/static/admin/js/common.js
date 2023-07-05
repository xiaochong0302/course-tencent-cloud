layui.extend({
    kgDropdown: '/static/lib/layui/extends/kg-dropdown',
    helper: '/static/lib/layui/extends/helper',
});

layui.use(['jquery', 'form', 'element', 'layer', 'kgDropdown'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;

    var $token = $('meta[name="csrf-token"]');

    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-Csrf-Token', $token.attr('content'));
        },
        statusCode: {
            400: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2, anim: 6});
            },
            401: function () {
                layer.msg('操作之前请先登录', {icon: 2, anim: 6});
            },
            403: function () {
                layer.msg('操作受限', {icon: 2, anim: 6});
            },
            404: function () {
                layer.msg('资源不存在', {icon: 2, anim: 6});
            },
            500: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2, anim: 6});
            }
        }
    });

    setInterval(function () {
        $.ajax({
            type: 'POST',
            url: '/token/refresh',
            success: function (res) {
                $token.attr('content', res.token);
            }
        });
    }, 300000);

    form.on('submit(go)', function (data) {
        var submit = $(this);
        var orgText = $(this).text();
        submit.text('提交中···').attr('disabled', 'disabled').addClass('layui-btn-disabled');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                if (res.msg) {
                    layer.msg(res.msg, {icon: 1, time: 1500});
                }
                if (res.location) {
                    var target = res.target || 'self';
                    setTimeout(function () {
                        if (target === 'parent') {
                            parent.location.href = res.location;
                        } else {
                            window.location.href = res.location;
                        }
                    }, 1000);
                }
                setTimeout(function () {
                    submit.text(orgText).removeAttr('disabled').removeClass('layui-btn-disabled');
                }, 1500);
            },
            error: function () {
                submit.text(orgText).removeAttr('disabled').removeClass('layui-btn-disabled');
            }
        });
        return false;
    });

    form.on('switch(published)', function (data) {
        var checked = $(this).is(':checked');
        var published = checked ? 1 : 0;
        var url = $(this).data('url');
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

    $('.kg-priority').on('change', function () {
        var priority = $(this).val();
        var url = $(this).data('url');
        $.ajax({
            type: 'POST',
            url: url,
            data: {priority: priority},
            success: function (res) {
                layer.msg(res.msg, {icon: 1});
            }
        });
    });

    $('.kg-delete,.kg-restore').on('click', function () {
        var url = $(this).data('url');
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
                }
            });
        });
    });

    $('.kg-back').on('click', function () {
        window.history.back();
    });

});