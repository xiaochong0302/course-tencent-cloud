{% extends 'templates/layer.volt' %}

{% block content %}

    {% set update_url = url({'for':'home.consult.update','id':consult.id}) %}

    <form class="layui-form consult-form" method="post" action="{{ update_url }}">
        {% if consult.course.id is defined %}
            <div class="layui-form-item">
                <label class="layui-form-label">课程</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" value="{{ consult.course.title }}" disabled="disabled">
                </div>
            </div>
        {% endif %}
        {% if consult.chapter.id is defined %}
            <div class="layui-form-item">
                <label class="layui-form-label">章节</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" value="{{ consult.chapter.title }}" disabled="disabled">
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label" for="answer">咨询</label>
            <div class="layui-input-block">
                <textarea id="answer" class="layui-textarea" name="question" lay-verify="required">{{ consult.question }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">私密</label>
            <div class="layui-input-block">
                <input type="radio" name="private" value="1" title="是" {% if consult.private == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="private" value="0" title="否" {% if consult.private == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

{% endblock %}