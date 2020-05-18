{% extends 'templates/base.volt' %}

{% block content %}

    {%- macro category_courses(courses) %}
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                {% for category in courses %}
                    <li {% if loop.first %}class="layui-this"{% endif %}>{{ category.name }}</li>
                {% endfor %}
            </ul>
            <div class="layui-tab-content">
                {% for category in courses %}
                    <div class="layui-tab-item {% if loop.first %}layui-show{% endif %}">
                        {% for course in category.courses %}
                            <div class="course-card">
                                <div class="cover"></div>
                                <div class="title">{{ course.title }}</div>
                                <div class="info"></div>
                            </div>
                        {% endfor %}
                    </div>
                {% endfor %}
            </div>
        </div>
    {%- endmacro %}

    <div class="index-module">
        <div class="layui-carousel" id="carousel">
            <div class="carousel" carousel-item>
                {% for slide in slides %}
                    <div class="item" style="background-color:{{ slide.bg_color }}">
                        <a href="{{ slide.url }}">
                            <img src="{{ slide.cover }}" alt="{{ slide.title }}">
                        </a>
                    </div>
                {% endfor %}
            </div>
        </div>
    </div>

    <div class="index-module">
        <div class="header">新上课程</div>
        <div class="content">
            {{ category_courses(new_courses) }}
        </div>
    </div>

    <div class="index-module">
        <div class="header">免费课程</div>
        <div class="content">
            {{ category_courses(free_courses) }}
        </div>
    </div>

    <div class="index-module">
        <div class="header">会员课程</div>
        <div class="content">
            {{ category_courses(vip_courses) }}
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        layui.use(['carousel'], function () {
            var carousel = layui.carousel;
            carousel.render({
                elem: '#carousel',
                width: '600px',
                height: '338px'
            });
        });
    </script>

{% endblock %}