{% extends 'templates/main.volt' %}

{% block content %}

    {% set edit_pwd_url = url({'for':'home.uc.account'},{'type':'password'}) %}
    {% set edit_phone_url = url({'for':'home.uc.account'},{'type':'phone'}) %}
    {% set edit_email_url = url({'for':'home.uc.account'},{'type':'email'}) %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="section">
                    <div class="my-nav">
                        <span class="title">账号安全</span>
                    </div>
                    <div class="security-item-list">
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-password"></i></span>
                                <span class="title">登录密码</span>
                                <span class="summary">经常更改密码有助于保护您的帐号安全</span>
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm" href="{{ edit_pwd_url }}">修改</a>
                            </div>
                        </div>
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-cellphone"></i></span>
                                <span class="title">手机绑定</span>
                                {% if account.phone %}
                                    <span class="summary">已绑定手机：{{ account.phone|anonymous }}</span>
                                {% else %}
                                    <span class="summary">可用于登录和重置密码</span>
                                {% endif %}
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm" href="{{ edit_phone_url }}">修改</a>
                            </div>
                        </div>
                        <div class="security-item">
                            <div class="info">
                                <span class="icon"><i class="layui-icon layui-icon-email"></i></span>
                                <span class="title">邮箱绑定</span>
                                {% if account.email %}
                                    <span class="summary">已绑定邮箱：{{ account.email|anonymous }}</span>
                                {% else %}
                                    <span class="summary">可用于登录和重置密码</span>
                                {% endif %}
                            </div>
                            <div class="action">
                                <a class="layui-btn layui-btn-sm" href="{{ edit_email_url }}">修改</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}
