{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.category.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加分类</legend>
        </fieldset>
        {% if parent.id > 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">父级</label>
                <div class="layui-input-block">
                    <select name="parent_id" lay-verify="required">
                        <option value="">选择父类</option>
                        {% for category in top_categories %}
                            <option value="{{ category.id }}" {% if category.id == parent.id %}selected{% endif %}>{{ category.name }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
        {% endif %}
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
                <input type="hidden" name="parent_id" value="{{ parent.id }}">
                <input type="hidden" name="type" value="{{ type }}">
            </div>
        </div>
    </form>

{% endblock %}