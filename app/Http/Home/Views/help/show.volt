{% extends 'templates/main.volt' %}

{% block content %}

    {% set courses_url = url({'for':'home.widget.featured_courses'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ url({'for':'home.help.index'}) }}">帮助中心</a>
            <a><cite>{{ help.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="page-info wrap">
                <div class="content ke-content kg-zoom">{{ help.content }}</div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="sidebar" id="course-list" data-url="{{ courses_url }}"></div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/help.show.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}
