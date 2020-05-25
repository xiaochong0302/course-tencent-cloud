{% extends 'templates/base.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ url({'for':'web.course.list'}) }}">全部课程</a>
            {% for path in category_paths %}
                <a href="{{ path.url }}">{{ path.name }}</a>
            {% endfor %}
            <a><cite>{{ course.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="course-meta">
                <div class="left"></div>
                <div class="right"></div>
            </div>
            <div class="layui-tab layui-tab-brief course-info-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">详情</li>
                    <li>目录</li>
                    <li>评价</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="course-details">{{ course.details }}</div>
                    </div>
                    <div class="layui-tab-item">
                        {{ partial('course/chapters', {'chapters':chapters}) }}
                    </div>
                    <div class="layui-tab-item">内容3</div>
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            {% if teachers %}
                {{ partial('course/sidebar_teachers') }}
            {% endif %}

            {% if topics %}
                {{ partial('course/sidebar_topics') }}
            {% endif %}

            {% if recommended_courses %}
                {{ partial('course/sidebar_recommended') }}
            {% endif %}

            {% if related_courses %}
                {{ partial('course/sidebar_related') }}
            {% endif %}
        </div>
    </div>

{% endblock %}