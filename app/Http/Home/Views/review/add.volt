{% extends 'templates/layer.volt' %}

{% block content %}

    {% set create_url = url({'for':'home.review.create'}) %}

    <form class="layui-form review-form" method="post" action="{{ create_url }}">
        <div class="layui-form-item mb0">
            <label class="layui-form-label">内容实用</label>
            <div class="layui-input-block">
                <div id="rating1"></div>
            </div>
        </div>
        <div class="layui-form-item mb0">
            <label class="layui-form-label">通俗易懂</label>
            <div class="layui-input-block">
                <div id="rating2"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">逻辑清晰</label>
            <div class="layui-input-block">
                <div id="rating3"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">评价内容</label>
            <div class="layui-input-block">
                <textarea name="content" class="layui-textarea" placeholder="请描述你的学习经历，例如学习成果、课程内容、讲师风格、教学服务等。" lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">匿名发布</label>
            <div class="layui-input-block">
                <input type="radio" name="anonymous" value="1" title="是">
                <input type="radio" name="anonymous" value="0" title="否" checked="checked">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                <input type="hidden" name="course_id" value="{{ request.get('course_id') }}">
                <input type="hidden" name="rating1" value="5">
                <input type="hidden" name="rating2" value="5">
                <input type="hidden" name="rating3" value="5">
            </div>
        </div>
    </form>
{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.review.js') }}

{% endblock %}
