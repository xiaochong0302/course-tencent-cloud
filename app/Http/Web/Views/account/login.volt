{% extends 'templates/base.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>登录</cite></a>
    </div>

    <div class="layui-tab layui-tab-brief login-tab">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">密码登录</li>
            <li>验证码登录</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('account/login_by_password') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('account/login_by_verify') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js',false) }}
    {{ js_include('web/js/captcha.login.js') }}
    {{ js_include('web/js/captcha.verify.js') }}

{% endblock %}