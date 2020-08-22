{% extends 'templates/main.volt' %}

{% block content %}

    {% set pager_url = url({'for':'desktop.teacher.pager'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>教师</cite></a>
    </div>

    <div id="teacher-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('desktop/js/teacher.list.js') }}
    {{ js_include('desktop/js/im.apply.js') }}

{% endblock %}