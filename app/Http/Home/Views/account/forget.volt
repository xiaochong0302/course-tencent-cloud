{% extends 'templates/main.volt' %}

{% block content %}

    {% set action_url = url({'for':'home.account.reset_pwd'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>重置密码</cite></a>
    </div>

    <div class="login-wrap wrap">
        <div class="layui-tab layui-tab-brief login-tab">
            <ul class="layui-tab-title login-tab-title">
                <li class="layui-this">手机方式</li>
                <li>邮箱方式</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    {{ partial('account/forget_by_phone') }}
                </div>
                <div class="layui-tab-item">
                    {{ partial('account/forget_by_email') }}
                </div>
            </div>
        </div>
        <div class="link">
            <a class="login-link" href="{{ url({'for':'home.account.login'}) }}">用户登录</a>
            <span class="separator">·</span>
            <a class="forget-link" href="{{ url({'for':'home.account.register'}) }}">用户注册</a>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/captcha.verify.phone.js') }}
    {{ js_include('home/js/captcha.verify.email.js') }}

{% endblock %}