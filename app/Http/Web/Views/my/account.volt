{% extends 'templates/full.volt' %}

{% block content %}

    {% set act_pwd_url = url({'for':'web.account.edit_pwd'}) %}
    {% set act_phone_url = url({'for':'web.account.edit_phone'}) %}
    {% set act_email_url = url({'for':'web.account.edit_email'}) %}

    <div class="layout-main">
        <div class="layout-sidebar">{{ partial('my/menu') }}</div>
        <div class="layout-content">
            <div class="container">
                <div class="my-nav-title">账号安全</div>
                <div class="security-item-list">
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-password"></i></span>
                        <span class="title">登录密码</span>
                        <span class="summary">经常更改密码有助于保护您的帐号安全</span>
                        <span class="action"><a href="javascript:" id="act-pwd-btn" class="layui-btn layui-btn-sm" data-url="{{ act_pwd_url }}">修改</a></span>
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-cellphone"></i></span>
                        <span class="title">手机绑定</span>
                        {% if account.phone %}
                            <span class="summary">已绑定手机：{{ account.phone }}</span>
                            <span class="action"><a href="javascript:" id="act-phone-btn" class="layui-btn layui-btn-sm" data-url="{{ act_phone_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a href="javascript:" id="act-phone-btn" class="layui-btn layui-btn-sm" data-url="{{ act_phone_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                    <div class="security-item">
                        <span class="icon"><i class="layui-icon layui-icon-email"></i></span>
                        <span class="title">邮箱绑定</span>
                        {% if account.phone %}
                            <span class="summary">已绑定邮箱：{{ account.email }}</span>
                            <span class="action"><a href="javascript:" id="act-email-btn" class="layui-btn layui-btn-sm" data-url="{{ act_email_url }}">修改</a></span>
                        {% else %}
                            <span class="summary">可用于登录和重置密码</span>
                            <span class="action"><a href="javascript:" id="act-email-btn" class="layui-btn layui-btn-sm" data-url="{{ act_email_url }}">绑定</a></span>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        var $ = layui.jquery;
        var layer = layui.layer;

        $('#act-pwd-btn').on('click', function () {
            showFrameLayer('密码修改', $(this).attr('data-url'));
        });

        $('#act-phone-btn').on('click', function () {
            showFrameLayer('手机绑定', $(this).attr('data-url'));

        });

        $('#act-email-btn').on('click', function () {
            showFrameLayer('邮箱绑定', $(this).attr('data-url'));
        });

        function showFrameLayer(title, url) {
            layer.open({
                type: 2,
                content: url,
                area: ['800px', '400px']
            });
        }

    </script>

{% endblock %}