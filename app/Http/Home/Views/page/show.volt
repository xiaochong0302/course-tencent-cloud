{% extends 'templates/main.volt' %}

{% block content %}

    {% set courses_url = url({'for':'home.widget.featured_courses'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>单页</cite></a>
            <a><cite>详情</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="article-info page-info wrap">
                <div class="title">{{ page.title }}</div>
                <div class="content markdown-body kg-zoom">{{ page.content }}</div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="sidebar" id="course-list" data-url="{{ courses_url }}"></div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/markdown.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/page.show.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}
