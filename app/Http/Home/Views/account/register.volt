{% extends 'templates/main.volt' %}

{% block content %}

    {% set register_with_phone = local_oauth.register_with_phone == 1 %}
    {% set register_with_email = local_oauth.register_with_email == 1 %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>注册</cite></a>
    </div>

    <div class="account-wrap wrap">
        <form class="layui-form account-form" method="POST" action="{{ url({'for':'home.account.do_register'}) }}">
            {% if register_with_phone and register_with_email %}
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-username"></label>
                    <input id="cv-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
                </div>
            {% elseif register_with_email %}
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-email"></label>
                    <input id="cv-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="邮箱" lay-verify="email">
                </div>
            {% else %}
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-cellphone"></label>
                    <input id="cv-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机" lay-verify="phone">
                </div>
            {% endif %}
            <div class="layui-form-item">
                <label class="layui-icon layui-icon-password"></label>
                <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码（字母数字特殊字符6-16位）" lay-verify="required">
            </div>
            <div class="layui-form-item">
                <div class="layui-input-inline verify-input-inline">
                    <label class="layui-icon layui-icon-vercode"></label>
                    <input class="layui-input" type="text" name="verify_code" placeholder="验证码" lay-verify="required">
                </div>
                <div class="layui-input-inline verify-btn-inline">
                    <button id="cv-emit-btn" class="layui-btn layui-btn-primary layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button id="cv-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">注册帐号</button>
                    <input type="hidden" name="return_url" value="{{ return_url }}">
                    <input id="cv-enabled" type="hidden" value="{{ captcha.enabled }}">
                    <input id="cv-app-id" type="hidden" value="{{ captcha.app_id }}">
                    <input id="cv-ticket" type="hidden" name="ticket">
                    <input id="cv-rand" type="hidden" name="rand">
                </div>
            </div>
        </form>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js',false) }}
    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}
