{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {%- macro category_courses(courses) %}
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                {% for category in courses %}
                    {% set class = loop.first ? 'layui-this' : 'none' %}
                    <li class="{{ class }}">{{ category.name }}</li>
                {% endfor %}
            </ul>
            <div class="layui-tab-content">
                {% for category in courses %}
                    {% set class = loop.first ? 'layui-tab-item layui-show' : 'layui-tab-item' %}
                    <div class="{{ class }}">
                        <div class="index-course-list clearfix">
                            <div class="layui-row layui-col-space20">
                                {% for course in category.courses %}
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
                {% for slide in slides %}
                    <div class="item" style="{{ slide.style }}">
                        <a href="{{ slide.url }}">
                            <img class="slide" src="{{ slide.cover }}" alt="{{ slide.title }}">
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