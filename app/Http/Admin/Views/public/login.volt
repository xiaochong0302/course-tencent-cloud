{% extends 'templates/main.volt' %}

{% block content %}

    {% set disabled_submit = captcha.enabled == 1 ? 'disabled="disabled"' : '' %}
    {% set disabled_class = captcha.enabled == 1 ? 'layui-btn-disabled' : '' %}

    <div class="kg-login-wrap">
        <div class="layui-card">
            <div class="layui-card-header">管理登录</div>
            <div class="layui-card-body">
                <form class="layui-form kg-login-form" method="POST" action="{{ url({'for':'admin.login'}) }}">
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
                            <button id="submit-btn" class="layui-btn layui-btn-fluid {{ disabled_class }}" {{ disabled_submit }} lay-submit="true" lay-filter="go">立即登录</button>
                            <input type="hidden" name="ticket">
                            <input type="hidden" name="rand">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="kg-login-copyright">
        Powered by <a href="{{ app_info.link }}" title="{{ app_info.name }}">{{ app_info.alias }} {{ app_info.version }}</a>
    </div>

{% endblock %}

{% block inline_css %}

    <style>
        body {
            background: #f2f2f2;
        }
    </style>

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

                new TencentCaptcha(
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