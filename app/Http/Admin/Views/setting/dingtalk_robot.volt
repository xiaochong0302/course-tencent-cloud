{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.dingtalk_robot'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>钉钉机器人</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">启用机器人</label>
            <div class="layui-input-block">
                <input type="radio" name="enabled" value="1" title="是" {% if robot.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="enabled" value="0" title="否" {% if robot.enabled == "0" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Webhook</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="webhook_url" value="{{ robot.webhook_url }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">加签密钥</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="app_secret" value="{{ robot.app_secret }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">技术手机号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="ts_mobiles" placeholder="值班技术人员手机号，多个号码逗号分隔" value="{{ robot.ts_mobiles }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">客服手机号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="cs_mobiles" placeholder="值班客服人员手机号，多个号码逗号分隔" value="{{ robot.cs_mobiles }}">
            </div>
        </div>
        <div class="layui-form-item" style="margin-top:20px;">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.dingtalk_robot'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>通知测试</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}