{% extends 'templates/base.volt' %}

{% block content %}

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
                        <div class="module-course-list clearfix">
                            {% for course in category.courses %}
                                <div class="course-card">
                                    <div class="cover">
                                        <a href="{{ url({'for':'web.course.show','id':course.id}) }}" title="{{ course.title }}">
                                            <img lay-src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
                                        </a>
                                    </div>
                                    <div class="title">
                                        <a href="{{ url({'for':'web.course.show','id':course.id}) }}" title="{{ course.title }}">{{ substr(course.title,0,15) }}</a>
                                    </div>
                                    <div class="meta">
                                        {% if course.market_price > 0 %}
                                            <span class="price">￥{{ course.market_price }}</span>
                                            <span class="level">中级</span>
                                            <span class="lesson">{{ course.lesson_count }}节课</span>
                                            <span class="user">{{ course.user_count }}人购买</span>
                                        {% else %}
                                            <span class="free">免费</span>
                                            <span class="level">中级</span>
                                            <span class="lesson">{{ course.lesson_count }}节课</span>
                                            <span class="user">{{ course.user_count }}人报名</span>
                                        {% endif %}
                                    </div>
                                </div>
                            {% endfor %}
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
    {%- endmacro %}

    <div class="index-module index-carousel">
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