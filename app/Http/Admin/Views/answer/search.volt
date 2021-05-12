{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.answer.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索回答</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">回答编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="回答编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">问题编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="question_id" placeholder="问题编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="owner_id" placeholder="用户编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布状态</label>
            <div class="layui-input-block">
                {% for value,title in publish_types %}
                    <input type="radio" name="published" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">匿名</label>
            <div class="layui-input-block">
                <input type="radio" name="anonymous" value="1" title="是">
                <input type="radio" name="anonymous" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">删除</label>
            <div class="layui-input-block">
                <input type="radio" name="deleted" value="1" title="是">
                <input type="radio" name="deleted" value="0" title="否">
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