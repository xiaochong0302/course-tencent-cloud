{% extends 'templates/main.volt' %}

{% block content %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核内容</legend>
    </fieldset>

    <form class="layui-form kg-form">
        {% if consult.course.id is defined %}
            <div class="layui-form-item">
                <label class="layui-form-label">课程名称</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid">{{ consult.course.title }}</div>
                </div>
            </div>
        {% endif %}
        {% if consult.chapter.id is defined %}
            <div class="layui-form-item">
                <label class="layui-form-label">章节名称</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid">{{ consult.chapter.title }}</div>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">咨询内容</label>
            <div class="layui-input-block">
                <div class="layui-form-mid">{{ consult.question }}</div>
            </div>
        </div>
    </form>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核意见</legend>
    </fieldset>

    {% set moderate_url = url({'for':'admin.consult.moderate','id':consult.id}) %}

    <form class="layui-form kg-form kg-mod-form" method="POST" action="{{ moderate_url }}">
        <div class="layui-form-item">
            <label class="layui-form-label">审核</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="approve" title="通过">
                <input type="radio" name="type" value="reject" title="拒绝">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button id="kg-submit" class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}