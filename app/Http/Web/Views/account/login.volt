{% extends 'templates/base.volt' %}

{% block content %}

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