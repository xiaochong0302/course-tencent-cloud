{% extends 'templates/main.volt' %}

{% block content %}

    {% set courses_url = url({'for':'home.topic.courses','id':topic.id}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>专题</cite></a>
        <a><cite>{{ topic.title }}</cite></a>
    </div>

    <div id="course-list" data-url="{{ courses_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/topic.show.js') }}

{% endblock %}