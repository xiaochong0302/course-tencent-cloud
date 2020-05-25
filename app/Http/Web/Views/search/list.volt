{% extends 'templates/base.volt' %}

{% block content %}

    {% set type = request.get('type','trim','course') %}

    {{ partial('partials/macro_course') }}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a href="#">搜索</a>
        <a><cite>{{ request.get('query')|striptags }}</cite></a>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            {% if type == 'course' %}
                {{ partial('search/content_course') }}
            {% elseif type == 'other' %}
                {{ partial('search/content_other') }}
            {% endif %}
            {{ partial('partials/pager') }}
        </div>
        <div class="layout-sidebar">
            {{ partial('search/sidebar') }}
        </div>
    </div>

{% endblock %}