{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.mail'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>邮件配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">发送邮箱</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_from_email" value="{{ mail.smtp_from_email }}" lay-verify="email">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发送人</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_from_name" value="{{ mail.smtp_from_name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">SMTP服务器</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_host" value="{{ mail.smtp_host }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">SMTP端口号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="smtp_port" value="{{ mail.smtp_port }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">加密类型</label>
            <div class="layui-input-block">
                <input type="radio" name="smtp_encryption" value="ssl" title="SSL" {% if mail.smtp_encryption == "ssl" %}checked="checked"{% endif %}>
                <input type="radio" name="smtp_encryption" value="tls" title="TLS" {% if mail.smtp_encryption == "tls" %}checked="checked"{% endif %}>
                <input type="radio" name="smtp_encryption" value="" title="不加密" {% if mail.smtp_encryption == "" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">账户验证</label>
            <div class="layui-input-block">
                <input type="radio" name="smtp_auth_enabled" value="1" title="是" lay-filter="smtp_auth_enabled" {% if mail.smtp_auth_enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="smtp_auth_enabled" value="0" title="否" lay-filter="smtp_auth_enabled" {% if mail.smtp_auth_enabled == "0" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div id="smtp-auth-block">
            <div class="layui-form-item">
                <label class="layui-form-label">SMTP帐号</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="smtp_username" value="{{ mail.smtp_username }}" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">SMTP密码</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="smtp_password" value="{{ mail.smtp_password }}" lay-verify="required">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.mail'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>邮件测试</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">收件邮箱</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="email" placeholder="请先提交相关配置，再进行邮件测试哦！" lay-verify="email">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(smtp_auth_enabled)', function (data) {
                var block = $('#smtp-auth-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}