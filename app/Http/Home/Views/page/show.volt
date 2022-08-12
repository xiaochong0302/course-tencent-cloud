{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>{{ page.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="page-info wrap">
                <div class="content ke-content">{{ page.content }}</div>
            </div>
        </div>
        <div class="layout-sidebar">
            {% if featured_courses %}
                <div class="sidebar">
                    <div class="layui-card">
                        <div class="layui-card-header">推荐课程</div>
                        <div class="layui-card-body">
                            {% for course in featured_courses %}
                                {{ sidebar_course_card(course) }}
                            {% endfor %}
                        </div>
                    </div>
                </div>
            {% endif %}
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}