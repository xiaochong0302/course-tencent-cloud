{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>登录绑定</cite></a>
    </div>

    <div class="login-wrap wrap">
        <div class="layui-tab layui-tab-brief login-tab">
            <ul class="layui-tab-title login-tab-title">
                <li class="layui-this">绑定已有帐号</li>
                <li>注册并绑定帐号</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    {{ partial('connect/bind_login') }}
                </div>
                <div class="layui-tab-item">
                    {{ partial('connect/bind_register') }}
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('https://ssl.captcha.qq.com/TCaptcha.js',false) }}
    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}