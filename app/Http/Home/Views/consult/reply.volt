{% extends 'templates/layer.volt' %}

{% block content %}

    {% set update_url = url({'for':'home.consult.reply','id':consult.id}) %}

    <form class="layui-form consult-form" method="post" action="{{ update_url }}">
        <div class="layui-form-item mb0">
            <label class="layui-form-label">课程</label>
            <div class="layui-form-mid">{{ consult.course.title }}</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">问题</label>
            <div class="layui-form-mid">{{ consult.question }}</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label" for="answer">回复</label>
            <div class="layui-input-block">
                <textarea id="answer" class="layui-textarea" name="answer" lay-verify="required">{{ consult.answer }}</textarea>
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

{% block inline_js %}

    <script>
        var index = parent.layer.getFrameIndex(window.name);
        parent.layer.iframeAuto(index);
    </script>

{% endblock %}