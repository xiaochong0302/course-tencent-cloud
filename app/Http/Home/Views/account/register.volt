{% extends 'templates/main.volt' %}

{% block content %}

    {% set register_with_phone = local_oauth.register_with_phone == 1 %}
    {% set register_with_email = local_oauth.register_with_email == 1 %}
    {% set terms_url = url({'for':'home.page.show','id':'terms'}) %}
    {% set privacy_url = url({'for':'home.page.show','id':'privacy'}) %}
    {% set action_url = url({'for':'home.account.do_register'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>用户注册</cite></a>
    </div>

    <div class="login-wrap wrap">
        <div class="layui-tab layui-tab-brief login-tab">
            <ul class="layui-tab-title login-tab-title">
                <li class="layui-this">手机注册</li>
                <li>邮箱注册</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    {{ partial('account/register_by_phone') }}
                </div>
                <div class="layui-tab-item">
                    {{ partial('account/register_by_email') }}
                </div>
            </div>
        </div>
        <div class="link">
            <a class="login-link" href="{{ url({'for':'home.account.login'}) }}">用户登录</a>
            <span class="separator">·</span>
            <a class="forget-link" href="{{ url({'for':'home.account.forget'}) }}">忘记密码</a>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/account.register.js') }}
    {{ js_include('home/js/captcha.verify.phone.js') }}
    {{ js_include('home/js/captcha.verify.email.js') }}

{% endblock %}
