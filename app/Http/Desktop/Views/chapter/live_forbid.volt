{% extends 'templates/main.volt' %}

{% block content %}

    {% set course_url = url({'for':'desktop.course.show','id':chapter.course.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

{% endblock %}