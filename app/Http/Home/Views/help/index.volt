{% extends 'templates/main.volt' %}

{% block content %}

    {% set courses_url = url({'for':'home.widget.featured_courses'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>帮助中心</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="wrap">
                <div class="layui-collapse">
                    {% for item in items %}
                        <div class="layui-colla-item">
                            <h2 class="layui-colla-title">{{ item.category.name }}</h2>
                            <div class="layui-colla-content layui-show">
                                <ul class="help-list">
                                    {% for help in item.helps %}
                                        {% set show_url = url({'for':'home.help.show','id':help.id}) %}
                                        <li><a href="{{ show_url }}" target="_blank"><i class="layui-icon layui-icon-right"></i>{{ help.title }}</a></li>
                                    {% endfor %}
                                </ul>
                            </div>
                        </div>
                    {% endfor %}
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="sidebar" id="course-list" data-url="{{ courses_url }}"></div>
        </div>
    </div>

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