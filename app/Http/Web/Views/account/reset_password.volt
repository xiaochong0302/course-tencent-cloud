{% extends 'templates/base.volt' %}

{% block content %}

    <div class="register-container">
        <form class="layui-form" method="POST" action="{{ url({'for':'web.account.do_reset_pwd'}) }}">
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="layui-icon layui-icon-username"></i></label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="layui-icon layui-icon-password"></i></label>
                <div class="layui-input-block">
                    <input class="layui-input" type="password" name="new_password" autocomplete="off" placeholder="新密码" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"><i class="layui-icon layui-icon-vercode"></i></label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="verify_code" placeholder="验证码" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <span id="captcha-btn" class="layui-btn layui-btn-primary layui-btn-fluid" data-app-id="{{ captcha.app_id }}">点击完成验证</span>
                    <span id="verify-tips" class="btn-verify layui-btn layui-btn-primary layui-btn-disabled layui-btn-fluid layui-hide"><i class="layui-icon layui-icon-ok"></i>验证成功</span>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button id="submit-btn" class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">立即重置</button>
                    <input type="hidden" name="ticket">
                    <input type="hidden" name="rand">
                </div>
            </div>
        </form>
    </div>
{% endblock %}

{% block inline_js %}

    <script src="https://ssl.captcha.qq.com/TCaptcha.js"></script>

    <script>
        var $ = layui.jquery;
        var layer = layui.layer;
        var captcha = new TencentCaptcha(
            $('#captcha-btn')[0],
            $('#captcha-btn').attr('data-app-id'),
            function (res) {
                if (res.ret === 0) {
                    $('input[name=ticket]').val(res.ticket);
                    $('input[name=rand]').val(res.randstr);
                    $.ajax({
                        type: 'POST',
                        url: '/verify/code',
                        data: {
                            account: $('input[name=account]').val(),
                            ticket: $('input[name=ticket]').val(),
                            rand: $('input[name=rand]').val()
                        },
                        success: function (res) {
                            var icon = res.code === 0 ? 1 : 2;
                            if (res.msg) {
                                layer.msg(res.msg, {icon: icon});
                            }

                        },
                    });
                    $('#captcha-btn').remove();
                    $('#submit-btn').removeAttr('disabled');
                    $('#verify-tips').removeClass('layui-hide');
                }
            }
        );
    </script>

{% endblock %}