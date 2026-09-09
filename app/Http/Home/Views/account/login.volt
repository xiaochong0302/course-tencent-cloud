{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>用户登录</cite></a>
    </div>

    <div class="login-wrap wrap">
        <div class="layui-tabs login-tab">
            <ul class="layui-tabs-header">
                <li class="layui-this">密码登录</li>
                <li>验证登录</li>
            </ul>
            <div class="layui-tabs-body">
                <div class="layui-tabs-item layui-show">
                    {{ partial('account/login_by_password') }}
                </div>
                <div class="layui-tabs-item">
                    {{ partial('account/login_by_verify') }}
                </div>
            </div>
        </div>
        <div class="link">
            <a class="login-link" href="{{ url({'for':'home.account.register'}) }}">用户注册</a>
            <span class="separator">·</span>
            <a class="forget-link" href="{{ url({'for':'home.account.forget'}) }}">忘记密码</a>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/captcha.verify.js') }}

{% endblock %}

{% block inline_js %}

    <script>
        if (window !== top) {
            top.location.href = window.location.href;
        }
    </script>

{% endblock %}
