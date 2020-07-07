{% extends 'templates/full.volt' %}

{% block content %}

    {% set pager_url = url({'for':'web.teacher.pager'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>名师</cite></a>
    </div>

    <div id="teacher-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/teacher.list.js') }}

{% endblock %}