{% extends 'templates/main.volt' %}

{% block content %}

    {% set course_id = request.get('course_id', 'int', '') %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.student.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索学员</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">课程编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="course_id" value="{{ course_id }}" placeholder="课程编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="用户编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">加入方式</label>
            <div class="layui-input-block">
                {% for value,title in source_types %}
                    <input type="radio" name="source_type" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}