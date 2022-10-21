{% extends 'templates/main.volt' %}

{% block content %}

    {% set pager_url = url({'for':'home.teacher.pager'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>师资</cite></a>
    </div>

    <div id="teacher-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/teacher.list.js') }}

{% endblock %}