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
                <div class="content ke-content">{{ help.content }}</div>
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

{% block inline_js %}

    <script>
        layui.use(['jquery', 'helper'], function () {
            var $ = layui.jquery;
            var helper = layui.helper;
            var $courseList = $('#course-list');
            if ($courseList.length > 0) {
                helper.ajaxLoadHtml($courseList.data('url'), $courseList.attr('id'));
            }
        });
    </script>

{% endblock %}