{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.captcha'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>验证码配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">App Id</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="app_id" value="{{ captcha.app_id }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Secret Key</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="secret_key" value="{{ captcha.secret_key }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.captcha'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>验证码测试</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label"><i class="layui-icon layui-icon-vercode"></i></label>
            <div class="layui-input-inline" style="width:200px;">
                <button type="button" id="front-captcha-btn" class="layui-btn layui-btn-primary layui-btn-fluid" data-app-id="{{ captcha.app_id }}">前台验证</button>
                <button type="button" id="front-verify-tips" class="kg-verify-btn layui-btn layui-btn-primary layui-btn-fluid layui-btn-disabled layui-hide"><i class="layui-icon layui-icon-ok"></i>前台验证成功</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><i class="layui-icon layui-icon-vercode"></i></label>
            <div class="layui-input-inline" style="width:200px;">
                <button type="button" id="back-verify-btn" class="layui-btn layui-btn-primary layui-btn-fluid" disabled="disabled" lay-submit="true" lay-filter="back_verify">后台验证</button>
                <button type="button" id="back-verify-tips" class="kg-verify-btn layui-btn layui-btn-primary layui-btn-fluid layui-btn-disabled layui-hide"><i class="layui-icon layui-icon-ok"></i>后台验证成功</button>
                <input type="hidden" name="ticket">
                <input type="hidden" name="rand">
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>

    <script>

        layui.use(['jquery', 'form', 'layer'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;

            var captcha = new TencentCaptcha(
                $('#front-captcha-btn')[0],
                $('#front-captcha-btn').data('app-id'),
                function (res) {
                    if (res.ret === 0) {
                        $('input[name=ticket]').val(res.ticket);
                        $('input[name=rand]').val(res.randstr);
                        $('#front-captcha-btn').remove();
                        $('#back-verify-btn').removeAttr('disabled');
                        $('#front-verify-tips').removeClass('layui-hide');
                    }
                }
            );

            form.on('submit(back_verify)', function (data) {
                $.ajax({
                    type: 'POST',
                    url: data.form.action,
                    data: data.field,
                    success: function (res) {
                        if (res.code === 0) {
                            $('#back-verify-btn').remove();
                            $('#back-verify-tips').removeClass('layui-hide');
                        }
                        layer.msg(res.msg, {icon: 1});
                    },
                    error: function (xhr) {
                        var json = JSON.parse(xhr.responseText);
                        layer.msg(json.msg, {icon: 2});
                    }
                });
                return false;
            });

        });

    </script>

{% endblock %}