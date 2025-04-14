{% extends 'templates/main.volt' %}

{% block content %}

    {% set course_url = url({'for':'home.course.show','id':chapter.course.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    <div class="live-preview">
        <div class="icon"><i class="layui-icon layui-icon-face-cry"></i></div>
        <div class="tips">直播已禁止，谢谢关注！</div>
    </div>

{% endblock %}
