var $ = layui.jquery;
var element = layui.element;
var form = layui.form;
var layer = layui.layer;
var util = layui.util;

$.ajaxSetup({
    beforeSend: function (xhr) {
        xhr.setRequestHeader('X-Csrf-Token', $('meta[name="csrf-token"]').attr('content'));
    }
});

util.fixbar({
    bar1: true,
    click: function (type) {
        console.log(type);
        if (type === 'bar1') {
            alert('点击了bar1');
        }
    }
});

form.on('submit(go)', function (data) {
    var submit = $(this);
    submit.attr('disabled', true).addClass('layui-btn-disabled');
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
                submit.attr('disabled', false).removeClass('layui-btn-disabled');
            }
        },
        error: function (xhr) {
            var json = JSON.parse(xhr.responseText);
            layer.msg(json.msg, {icon: 2});
            submit.attr('disabled', false).removeClass('layui-btn-disabled');
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