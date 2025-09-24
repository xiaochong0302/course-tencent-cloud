{% extends 'templates/main.volt' %}

{% block content %}

    {% set terms_url = url({'for':'home.page.show','id':'terms'}) %}
    {% set privacy_url = url({'for':'home.page.show','id':'privacy'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>登录绑定</cite></a>
    </div>

    <div class="login-wrap wrap">
        <div class="layui-tabs login-tab">
            <ul class="layui-tabs-header">
                <li class="layui-this">绑定已有帐号</li>
                <li>注册并绑定帐号</li>
            </ul>
            <div class="layui-tabs-body">
                <div class="layui-tabs-item layui-show">
                    {{ partial('connect/bind_login') }}
                </div>
                <div class="layui-tabs-item">
                    {{ partial('connect/bind_register') }}
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/connect.bind.js') }}
    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}
