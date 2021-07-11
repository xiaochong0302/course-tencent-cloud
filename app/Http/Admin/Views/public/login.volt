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
                        <input class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-icon layui-icon-password"></label>
                        <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
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

    {% if captcha.enabled == 1 %}

        {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js', false) }}

    {% endif %}

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

    {% if captcha.enabled == 1 %}
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