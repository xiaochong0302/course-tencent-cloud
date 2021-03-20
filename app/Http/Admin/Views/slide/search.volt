{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.slide.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索轮播</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">轮播编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="轮播编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">轮播标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" placeholder="轮播标题模糊匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">目标类型</label>
            <div class="layui-input-block">
                {% for value,title in target_types %}
                    <input type="radio" name="target" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是">
                <input type="radio" name="published" value="0" title="否">
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