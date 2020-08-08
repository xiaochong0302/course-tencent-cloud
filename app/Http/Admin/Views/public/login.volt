{% extends 'templates/main.volt' %}

{% block content %}

    <form class="kg-login-form layui-form" method="POST" action="{{ url({'for':'admin.login'}) }}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
            </div>
        </div>
        {% if captcha.enabled == 1 %}
            <div id="captcha-block" class="layui-form-item">
                <div class="layui-input-block">
                    <button id="captcha-btn" class="layui-btn layui-btn-fluid" type="button" data-app-id="{{ captcha.app_id }}">点击完成验证</button>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <div class="layui-input-block">
                {% set disabled = captcha.enabled ? 'disabled' : '' %}
                <button id="submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" {{ disabled }} lay-submit="true" lay-filter="go">立即登录</button>
                <input type="hidden" name="ticket">
                <input type="hidden" name="rand">
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>
        if (window !== top) {
            top.location.href = window.location.href;
        }
    </script>

    {% if captcha.enabled == 1 %}

        {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js', false) }}

        <script>

            layui.use(['jquery', 'form'], function () {
                var $ = layui.jquery;
                var captcha = new TencentCaptcha(
                    $('#captcha-btn')[0],
                    $('#captcha-btn').data('app-id'),
                    function (res) {
                        if (res.ret === 0) {
                            $('input[name=ticket]').val(res.ticket);
                            $('input[name=rand]').val(res.randstr);
                            $('#captcha-block').hide();
                            $('#submit-btn').removeClass('layui-btn-disabled').removeAttr('disabled');
                        }
                    }
                );
            });

        </script>

    {% endif %}

{% endblock %}