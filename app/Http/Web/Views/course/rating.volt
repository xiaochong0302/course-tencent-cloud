{% extends 'templates/layer.volt' %}

{% block content %}
    <form class="layui-form rating-form" method="post" action="{{ url({'for':'web.review.create'}) }}">
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
                <textarea name="content" class="layui-textarea" placeholder="请描述你的学习经历，例如学习成果、课程内容、讲师风格、教学服务等。"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="layui-btn layui-btn-primary cancel-rating">取消</button>
                <input type="hidden" name="course_id" value="{{ course.id }}">
                <input type="hidden" name="rating1" value="5">
                <input type="hidden" name="rating2" value="5">
                <input type="hidden" name="rating3" value="5">
            </div>
        </div>
    </form>
{% endblock %}

{% block include_js %}

    {{ js_include('web/js/course.rating.js') }}

{% endblock %}