{% extends 'templates/layer.volt' %}

{% block content %}
    <form class="layui-form consult-form" method="post" action="{{ url({'for':'home.consult.create'}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label" for="question">问题</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" id="question" name="question" placeholder="请详细描述问题，我们会尽快回复您" lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">私密</label>
            <div class="layui-input-block">
                <input type="radio" name="private" value="1" title="是">
                <input type="radio" name="private" value="0" title="否" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                <input type="hidden" name="course_id" value="{{ request.get('course_id') }}">
            </div>
        </div>
    </form>
{% endblock %}