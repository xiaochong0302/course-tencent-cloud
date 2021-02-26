{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.secret'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>密钥配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">App Id</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="app_id" value="{{ secret.app_id }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Secret Id</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="secret_id" value="{{ secret.secret_id }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Secret Key</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="secret_key" value="{{ secret.secret_key }}" lay-verify="required">
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