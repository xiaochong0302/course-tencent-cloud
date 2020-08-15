{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {% set types = {'course':'课程','group':'群组','user':'用户'} %}
    {% set type = request.get('type','trim','course') %}
    {% set query = request.get('query','striptags','') %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a href="#">搜索</a>
        <a><cite>{{ query }}</cite></a>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="search-tab wrap">
                {% for key,value in types %}
                    {% set class = (type == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                    {% set url = url({'for':'web.search.index'},{'type':key,'query':query}) %}
                    <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                {% endfor %}
            </div>
            {% if pager.total_pages > 0 %}
                <div class="wrap">
                    {% if type == 'course' %}
                        {{ partial('search/course') }}
                    {% elseif type == 'group' %}
                        {{ partial('search/group') }}
                    {% elseif type == 'user' %}
                        {{ partial('search/user') }}
                    {% endif %}
                </div>
                {{ partial('partials/pager') }}
            {% else %}
                <div class="search-empty wrap">
                    <div class="icon"><i class="layui-icon layui-icon-face-surprised"></i></div>
                    <div class="text">没有找到<span class="query">{{ query }}</span>相关内容，换个关键字试试吧！</div>
                </div>
            {% endif %}
        </div>
        <div class="layout-sidebar">
            {{ partial('search/sidebar') }}
        </div>
    </div>

{% endblock %}