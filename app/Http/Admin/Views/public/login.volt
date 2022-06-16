{% extends 'templates/main.volt' %}

{% block content %}

    {% set disabled_submit = captcha.enabled == 1 ? 'disabled="disabled"' : '' %}
    {% set disabled_class = captcha.enabled == 1 ? 'layui-btn-disabled' : '' %}

    <div class="kg-login-wrap">
        <div class="layui-card">
            <div class="layui-card-header">后台登录</div>
            <div class="layui-card-body">
                <form class="layui-form kg-login-form" method="POST" action="{{ url({'for':'admin.login'}) }}">
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-username"></label>
                        <input id="cl-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-password"></label>
                        <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
                    </div>
                    {% if captcha.enabled == 1 %}
                        <div id="captcha-block" class="layui-form-item">
                            <div class="layui-input-block">
                                <button id="cl-emit-btn" class="layui-btn layui-btn-fluid" type="button">点击完成验证</button>
                            </div>
                        </div>
                    {% endif %}
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button id="cl-submit-btn" class="layui-btn layui-btn-fluid {{ disabled_class }}" {{ disabled_submit }} lay-submit="true" lay-filter="go">立即登录</button>
                            <input id="cl-captcha-enabled" type="hidden" value="{{ captcha.enabled }}">
                            <input id="cl-captcha-appId" type="hidden" value="{{ captcha.app_id }}">
                            <input id="cl-captcha-ticket" type="hidden" name="captcha[ticket]">
                            <input id="cl-captcha-rand" type="hidden" name="captcha[rand]">
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
        html {
            height: 95%;
        }

        body {
            background: #16a085;
        }

        .circles {
            display: block;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            position: absolute;
            opacity: 0.5;
            z-index: -1;
        }
    </style>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/jquery.min.js') }}
    {{ js_include('lib/jquery.buoyant.min.js') }}
    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js', false) }}

{% endblock %}

{% block inline_js %}

    <script>
        if (window !== top) {
            top.location.href = window.location.href;
        }
    </script>

    <script>
        $('body').buoyant({
            elementClass: 'circles',
            numberOfItems: 20,
            minRadius: 5,
            maxRadius: 30,
        });
    </script>

    <script>
        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            if ($('#cl-captcha-enabled').val() === '1') {
                var captcha = new TencentCaptcha(
                    $('#cl-emit-btn')[0],
                    $('#cl-captcha-appId').val(),
                    function (res) {
                        if (res.ret === 0) {
                            $('#cl-captcha-ticket').val(res.ticket);
                            $('#cl-captcha-rand').val(res.randstr);
                            $('#cl-submit-btn').removeClass('layui-btn-disabled').removeAttr('disabled');
                            $('#captcha-block').hide();
                        }
                    }
                );
            }

        });
    </script>

{% endblock %}