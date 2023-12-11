{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {% set types = {'course':'课程','article':'专栏','question':'问答'} %}
    {% set type = request.get('type','trim','course') %}
    {% set query = request.get('query','striptags','') %}

    <form class="layui-form search-form" method="GET" action="{{ url({'for':'home.search.index'}) }}">
        <div class="layui-form-item">
            <div class="layui-inline">
                <input class="layui-input query-input" type="text" name="query" value="{{ query }}" lay-verify="required">
            </div>
            <div class="layui-inline">
                <button class="layui-btn search-btn" lay-submit="true" lay-filter="search">搜索</button>
                <input type="hidden" name="type" value="{{ type }}">
            </div>
        </div>
    </form>

    <div class="layout-main">
        <div class="layout-content">
            <div class="search-tab-wrap wrap">
                <div class="layui-tab layui-tab-brief search-tab">
                    <ul class="layui-tab-title">
                        {% for key,value in types %}
                            {% set class = (type == key) ? 'layui-this' : 'none' %}
                            {% set url = url({'for':'home.search.index'},{'type':key,'query':query}) %}
                            <li class="{{ class }}"><a href="{{ url }}">{{ value }}</a></li>
                        {% endfor %}
                    </ul>
                    <div class="layui-tab-content">
                        {% if type == 'course' %}
                            <div class="layui-tab-item layui-show">
                                {{ partial('search/course') }}
                            </div>
                        {% elseif type == 'article' %}
                            <div class="layui-tab-item layui-show">
                                {{ partial('search/article') }}
                            </div>
                        {% elseif type == 'question' %}
                            <div class="layui-tab-item layui-show">
                                {{ partial('search/question') }}
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

{% block include_js %}

    {{ js_include('home/js/search.index.js') }}

{% endblock %}