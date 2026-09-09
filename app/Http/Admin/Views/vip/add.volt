{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.vip.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加套餐</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">期限</label>
            <div class="layui-input-block">
                <select name="expiry" lay-verify="required">
                    <option value="">请选择</option>
                    {% for value in 1..60 %}
                        <option value="{{ value }}">{{ value }}个月</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">价格</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="price" lay-verify="number">
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