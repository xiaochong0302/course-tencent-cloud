{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

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

{% block include_js %}

    {{ js_include('home/js/help.js') }}

{% endblock %}