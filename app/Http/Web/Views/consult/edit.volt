{% extends 'templates/layer.volt' %}

{% block content %}

    {% set update_url = url({'for':'web.consult.update','id':consult.id}) %}

    <form class="layui-form" method="post" action="{{ update_url }}">
        <div class="layui-form-item mb0">
            <label class="layui-form-label">课程</label>
            <div class="layui-form-mid">{{ consult.course.title }}</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">章节</label>
            <div class="layui-form-mid">{{ consult.chapter.title }}</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">咨询内容</label>
            <div class="layui-input-block">
                <textarea name="question" class="layui-textarea" lay-verify="required">{{ consult.question }}</textarea>
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