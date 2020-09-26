{% extends 'templates/main.volt' %}

{% block content %}

    {% set edit_pwd_url = url({'for':'home.account.edit_pwd'}) %}
    {% set edit_phone_url = url({'for':'home.account.edit_phone'}) %}
    {% set edit_email_url = url({'for':'home.account.edit_email'}) %}

    <div class="layout-main clearfix">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">账号安全</span>
                </div>
                <div class="security-item-list">
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-password"></i></span>
                        <span class="title">登录密码</span>
                        <span class="summary">经常更改密码有助于保护您的帐号安全</span>
                        <span class="action"><a class="layui-btn layui-btn-sm btn-edit-pwd" href="javascript:" data-url="{{ edit_pwd_url }}">修改</a></span>
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-cellphone"></i></span>
                        <span class="title">手机绑定</span>
                        {% if account.phone %}
                            <span class="summary">已绑定手机：{{ account.phone|anonymous }}</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-phone" href="javascript:" data-url="{{ edit_phone_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-phone" href="javascript:" data-url="{{ edit_phone_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-email"></i></span>
                        <span class="title">邮箱绑定</span>
                        {% if account.phone %}
                            <span class="summary">已绑定邮箱：{{ account.email|anonymous }}</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-email" href="javascript:" data-url="{{ edit_email_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a class="layui-btn layui-btn-sm btn-edit-email" href="javascript:" data-url="{{ edit_email_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.account.js') }}

{% endblock %}