{% extends 'templates/main.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ url({'for':'desktop.help.index'}) }}">帮助</a>
            <a><cite>{{ help.title }}</cite></a>
        </span>
    </div>

    <div class="page-info wrap">
        <div class="title">{{ help.title }}</div>
        <div class="content" id="preview">{{ help.content }}</div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/method.min.js', false) }}
    {{ js_include('desktop/js/markdown.preview.js') }}

{% endblock %}