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

    <div class="course-meta">
        <div class="left"></div>
        <div class="right"></div>
    </div>

    <div class="course-body">
        <div class="left">
            <div class="layui-tab layui-tab-brief">
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
        <div class="right">
            {% if teachers %}
                {{ partial('course/widget_teacher', {'teachers':teachers}) }}
            {% endif %}

            {% if topics %}
                {{ partial('course/widget_topic', {'topics':topics}) }}
            {% endif %}

            {% if recommended_courses %}
                {{ partial('course/widget_recommended', {'courses':recommended_courses}) }}
            {% endif %}

            {% if related_courses %}
                {{ partial('course/widget_related', {'courses':related_courses}) }}
            {% endif %}
        </div>
    </div>

{% endblock %}