{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>用户登录</cite></a>
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
            <a class="login-link" href="{{ url({'for':'home.account.register'}) }}">用户注册</a>
            <span class="separator">·</span>
            <a class="forget-link" href="{{ url({'for':'home.account.forget'}) }}">忘记密码</a>
        </div>
        <div class="oauth">
            {% if oauth_provider.qq.enabled == 1 %}
                <a class="layui-icon layui-icon-login-qq login-qq" href="{{ url({'for':'home.oauth.qq'}) }}"></a>
            {% endif %}
            {% if oauth_provider.weixin.enabled == 1 %}
                <a class="layui-icon layui-icon-login-wechat login-wechat" href="{{ url({'for':'home.oauth.weixin'}) }}"></a>
            {% endif %}
            {% if oauth_provider.weibo.enabled == 1 %}
                <a class="layui-icon layui-icon-login-weibo login-weibo" href="{{ url({'for':'home.oauth.weibo'}) }}"></a>
            {% endif %}
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