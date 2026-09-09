{% extends 'templates/main.volt' %}

{% block content %}

    {% set parent_id = request.get('parent_id','int',0) %}
    {% set type = request.get('type','int',1) %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.category.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加分类</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">上级</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-search="true">
                    <option value="0">请选择</option>
                    {% for option in category_options %}
                        {% set selected = option.id == parent_id ? 'selected' : '' %}
                        <option value="{{ option.id }}" {{ selected }}>{{ option.name }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="10" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                <input type="hidden" name="type" value="{{ type }}">
            </div>
        </div>
    </form>

{% endblock %}
