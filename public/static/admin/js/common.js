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

    form.on('switch(go)', function (data) {
        var postData = {};
        var name = $(this).attr('name');
        var checked = $(this).is(':checked');
        var value = checked ? 1 : 0;
        var url = $(this).data('url');
        var onTips = $(this).data('on-tips');
        var offTips = $(this).data('off-tips');
        var tips = '确定要执行操作？';
        if (value === 1 && onTips) {
            tips = onTips;
        } else if (value === 0 && offTips) {
            tips = offTips;
        }
        postData[name] = value;
        layer.confirm(tips, {
            cancel: function (index) {
                layer.close(index);
                data.elem.checked = !checked;
                form.render();
            }
        }, function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: postData,
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

    form.on('checkbox(all)', function (data) {
        $('input:checkbox[class="item"]').each(function (index, item) {
            item.checked = data.elem.checked;
        });
        form.render('checkbox');
    });

    form.on('checkbox(item)', function (data) {
        var allChecked = $('input:checkbox[class="item"]:not(:checked)').length === 0;
        $('input:checkbox[class="all"]').prop('checked', allChecked);
        form.render('checkbox');
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

    $('.kg-batch').on('click', function () {
        var url = $(this).data('url');
        var tips = $(this).data('tips');
        var defaultTips = '确定要执行批量操作吗？';
        var ids = [];
        $('input:checkbox[class="item"]:checked').each(function (index, item) {
            ids.push(item.value);
        });
        if (ids.length === 0) {
            layer.msg('没有选中任何条目', {icon: 2});
            return false;
        }
        tips = tips || defaultTips;
        layer.confirm(tips, function () {
            $.ajax({
                type: 'POST',
                url: url,
                data: {'ids': ids},
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