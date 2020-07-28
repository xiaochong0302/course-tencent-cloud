{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>登录</cite></a>
    </div>

    <div class="login-wrap wrap">
        <div class="layui-tab layui-tab-brief login-tab">
            <ul class="layui-tab-title login-tab-title">
                <li class="layui-this">密码登录</li>
                <li>验证登录</li>
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
        <div class="link">
            <a class="login-link" href="{{ url({'for':'web.account.register'}) }}">免费注册</a>
            <span class="separator">·</span>
            <a class="forget-link" href="{{ url({'for':'web.account.forget_pwd'}) }}">忘记密码</a>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js',false) }}
    {{ js_include('web/js/captcha.login.js') }}
    {{ js_include('web/js/captcha.verify.js') }}

{% endblock %}