{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {%- macro category_courses(items) %}
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                {% for item in items %}
                    {% set class = loop.first ? 'layui-this' : 'none' %}
                    <li class="{{ class }}">{{ item.category.name }}</li>
                {% endfor %}
            </ul>
            <div class="layui-tab-content">
                {% for item in items %}
                    {% set class = loop.first ? 'layui-tab-item layui-show' : 'layui-tab-item' %}
                    <div class="{{ class }}">
                        <div class="index-course-list clearfix">
                            <div class="layui-row layui-col-space20">
                                {% for course in item.courses %}
                                    <div class="layui-col-md3">
                                        {{ course_card(course) }}
                                    </div>
                                {% endfor %}
                            </div>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    {%- endmacro %}

    <div class="index-wrap index-carousel wrap">
        <div class="layui-carousel" id="carousel">
            <div class="carousel" carousel-item>
                {% for carousel in carousels %}
                    <div class="item" style="{{ carousel.style }}">
                        <a href="{{ carousel.url }}">
                            <img class="carousel" src="{{ carousel.cover }}" alt="{{ carousel.title }}">
                        </a>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>

    <div class="index-wrap wrap">
        <div class="header">新上课程</div>
        <div class="content">
            {{ category_courses(new_courses) }}
        </div>
    </div>

    <div class="index-wrap wrap">
        <div class="header">免费课程</div>
        <div class="content">
            {{ category_courses(free_courses) }}
        </div>
    </div>

    <div class="index-wrap wrap">
        <div class="header">会员课程</div>
        <div class="content">
            {{ category_courses(vip_courses) }}
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/index.js') }}

{% endblock %}