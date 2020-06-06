{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

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
                            {% for course in category.courses %}
                                {{ course_card(course) }}
                            {% endfor %}
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    {%- endmacro %}

    <div class="index-container index-carousel container">
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

    <div class="index-container container">
        <div class="header">新上课程</div>
        <div class="content">
            {{ category_courses(new_courses) }}
        </div>
    </div>

    <div class="index-container container">
        <div class="header">免费课程</div>
        <div class="content">
            {{ category_courses(free_courses) }}
        </div>
    </div>

    <div class="index-container container">
        <div class="header">会员课程</div>
        <div class="content">
            {{ category_courses(vip_courses) }}
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        layui.use(['carousel', 'flow'], function () {
            var carousel = layui.carousel;
            var flow = layui.flow;
            carousel.render({
                elem: '#carousel',
                width: '100%',
                height: '270px'
            });
            flow.lazyimg();
        });
    </script>

{% endblock %}