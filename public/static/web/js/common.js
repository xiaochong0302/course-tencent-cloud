layui.define(['jquery', 'element'], function (exports) {
    exports('ajaxLoadHtml', function (url, target) {
        var $ = layui.jquery;
        var element = layui.element;
        var $target = $('#' + target);
        var html = '<div class="loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i></div>';
        $target.html(html);
        $.get(url, function (html) {
            $target.html(html);
            element.init();
        });
    });
});

layui.use(['jquery', 'form', 'element', 'layer'], function () {

    var $ = layui.jquery;
    var element = layui.element;
    var form = layui.form;
    var layer = layui.layer;

    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-Csrf-Token', $('meta[name="csrf-token"]').attr('content'));
        }
    });

    /**
     * @todo 定时刷新token
     */
    /**
     setInterval(function () {
        var $token = $('meta[name="csrf-token"]');
        $.ajax({
            type: 'POST',
            url: '/token/refresh',
            data: {token: $token.val()},
            success: function (res) {
                $token.attr('content', res.token);
            }
        });
    }, 300000);
     */

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
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg, {icon: 2});
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            }
        });
        return false;
    });

    $('.kg-delete').on('click', function () {
        var url = $(this).attr('data-url');
        var tips = '确定要删除吗？';
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

    $('body').on('click', '.layui-laypage > a', function () {
        var url = $(this).attr('data-url');
        var target = $(this).attr('data-target');
        layui.ajaxLoadHtml(url, target);
    });

});