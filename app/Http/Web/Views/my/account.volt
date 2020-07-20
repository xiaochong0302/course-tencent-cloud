{% extends 'templates/full.volt' %}

{% block content %}

    {% set act_pwd_url = url({'for':'web.account.edit_pwd'}) %}
    {% set act_phone_url = url({'for':'web.account.edit_phone'}) %}
    {% set act_email_url = url({'for':'web.account.edit_email'}) %}

    <div class="layout-main">
        <div class="layout-sidebar">{{ partial('my/menu') }}</div>
        <div class="layout-content">
            <div class="wrap">
                <div class="my-nav-title">账号安全</div>
                <div class="security-item-list">
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-password"></i></span>
                        <span class="title">登录密码</span>
                        <span class="summary">经常更改密码有助于保护您的帐号安全</span>
                        <span class="action"><a class="layui-btn layui-btn-sm" href="{{ act_pwd_url }}">修改</a></span>
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-cellphone"></i></span>
                        <span class="title">手机绑定</span>
                        {% if account.phone %}
                            <span class="summary">已绑定手机：{{ account.phone|anonymous }}</span>
                            <span class="action"><a class="layui-btn layui-btn-sm" href="{{ act_phone_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a class="layui-btn layui-btn-sm" href="{{ act_phone_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-email"></i></span>
                        <span class="title">邮箱绑定</span>
                        {% if account.phone %}
                            <span class="summary">已绑定邮箱：{{ account.email|anonymous }}</span>
                            <span class="action"><a class="layui-btn layui-btn-sm" href="{{ act_email_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a class="layui-btn layui-btn-sm" href="{{ act_email_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}