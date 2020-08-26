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

    {% set tab_show = type %}
    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="search-tab-wrap wrap">
                <div class="layui-tab layui-tab-brief search-tab">
                    <ul class="layui-tab-title">
                        {% for key,value in types %}
                            {% set class = (type == key) ? 'layui-this' : 'none' %}
                            {% set url = url({'for':'desktop.search.index'},{'type':key,'query':query}) %}
                            <li class="{{ class }}"><a href="{{ url }}">{{ value }}</a></li>
                        {% endfor %}
                    </ul>
                    <div class="layui-tab-content">
                        {% if type == 'course' %}
                            <div class="layui-tab-item layui-show">
                                {{ partial('search/course') }}
                            </div>
                        {% elseif type == 'group' %}
                            <div class="layui-tab-item layui-show">
                                {{ partial('search/group') }}
                            </div>
                        {% elseif type == 'user' %}
                            <div class="layui-tab-item layui-show">
                                {{ partial('search/user') }}
                            </div>
                        {% endif %}
                    </div>
                </div>
            </div>
            {{ partial('partials/pager') }}
        </div>
        <div class="layout-sidebar">
            {{ partial('search/sidebar') }}
        </div>
    </div>

{% endblock %}