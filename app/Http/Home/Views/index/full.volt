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

    <div class="index-carousel wrap">
        <div class="layui-carousel" id="carousel">
            <div class="carousel" carousel-item>
                {% for slide in slides %}
                    <div class="item">
                        <a href="{{ slide.url }}">
                            <img class="carousel" src="{{ slide.cover }}!slide_1100" alt="{{ slide.title }}">
                        </a>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>

    <div class="index-wrap wrap">
        <div class="header">推荐课程</div>
        <div class="content">
            {{ category_courses(featured_courses) }}
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

    {{ js_include('home/js/index.js') }}

{% endblock %}