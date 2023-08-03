{% extends 'templates/layer.volt' %}

{% block content %}

    <form class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">验证算式</label>
            <div class="layui-input-block">
                <img id="img-captcha" class="pointer" title="刷新表达式" alt="验证表达式" width="200" height="50">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">计算结果</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="rand">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="captcha">提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

    <div class="layui-hide">
        <input type="hidden" name="account" value="{{ request.get('account') }}">
        <input type="hidden" name="type" value="{{ request.get('type') }}">
        <input type="hidden" name="ticket">
    </div>

{% endblock %}

{% block include_js %}

    <script>
        layui.use(['jquery', 'form', 'layer', 'helper'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;
            var index = parent.layer.getFrameIndex(window.name);

            var verify = {
                account: $('input[name=account]').val(),
                type: $('input[name=type]').val(),
            }

            var showCaptchaImage = function () {
                $.get('/api/verify/captcha', function (res) {
                    $('#img-captcha').attr('src', res.captcha.content);
                    $('input[name=ticket]').val(res.captcha.ticket);
                });
            };

            $('#img-captcha').on('click', function () {
                showCaptchaImage();
            });

            form.on('submit(captcha)', function (data) {
                var submit = $(this);
                var account = $('input[name=account]').val();
                var ticket = $('input[name=ticket]').val();
                var rand = $('input[name=rand]').val();
                submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
                if (verify.type === 'phone') {
                    parent.layui.$('#cv-phone-submit-btn').removeAttr('disabled').removeClass('layui-btn-disabled');
                    parent.layui.$('#cv-phone-captcha-ticket').val(ticket);
                    parent.layui.$('#cv-phone-captcha-rand').val(rand);
                } else if (verify.type === 'email') {
                    parent.layui.$('#cv-email-submit-btn').removeAttr('disabled').removeClass('layui-btn-disabled');
                    parent.layui.$('#cv-email-captcha-ticket').val(ticket);
                    parent.layui.$('#cv-email-captcha-rand').val(rand);
                } else {
                    parent.layui.$('#cv-submit-btn').removeAttr('disabled').removeClass('layui-btn-disabled');
                    parent.layui.$('#cv-captcha-ticket').val(ticket);
                    parent.layui.$('#cv-captcha-rand').val(rand);
                }
                $.ajax({
                    type: 'POST',
                    url: '/api/verify/code',
                    data: {
                        account: account,
                        ticket: ticket,
                        rand: rand,
                    },
                    success: function () {
                        layer.msg('发送验证码成功', {icon: 1});
                        setTimeout(function () {
                            parent.layer.close(index);
                        }, 1500);
                    }, error: function () {
                        submit.removeAttr('disabled').removeClass('layui-btn-disabled');
                    }
                });
                return false;
            });

            showCaptchaImage();

        });
    </script>

{% endblock %}
