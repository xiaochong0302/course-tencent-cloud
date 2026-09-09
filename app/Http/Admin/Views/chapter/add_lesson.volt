{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加课时</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">章节</label>
            <div class="layui-input-block">
                <select name="parent_id" lay-verify="required" lay-search="true">
                    <option value="">请选择</option>
                    {% for chapter in chapters %}
                        {% set selected = parent_id == chapter.id ? 'selected' : '' %}
                        <option value="{{ chapter.id }}" {{ selected }}>{{ chapter.title }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">类型</label>
            <div class="layui-input-block">
                {% for value,title in model_types %}
                    {% set checked = value == 1 ? 'checked' : '' %}
                    <input type="radio" name="model" value="{{ value }}" title="{{ title }}" {{ checked }}>
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                <input type="hidden" name="course_id" value="{{ course.id }}">
            </div>
        </div>
    </form>

{% endblock %}
