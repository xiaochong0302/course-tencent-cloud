{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {%- macro show_courses(courses) %}
        <div class="index-course-list">
            <div class="layui-row layui-col-space20">
                {% for course in courses %}
                    <div class="layui-col-md3">
                        {{ course_card(course) }}
                    </div>
                {% endfor %}
            </div>
        </div>
    {%- endmacro %}

    {% if slides|length > 0 %}
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
    {% endif %}

    {% if featured_courses|length > 0 %}
        <div class="index-wrap wrap">
            <div class="header">推荐课程</div>
            <div class="content simple">
                {{ show_courses(featured_courses) }}
            </div>
        </div>
    {% endif %}

    {% if new_courses|length > 0 %}
        <div class="index-wrap wrap">
            <div class="header">新上课程</div>
            <div class="content simple">
                {{ show_courses(new_courses) }}
            </div>
        </div>
    {% endif %}

    {% if free_courses|length > 0 %}
        <div class="index-wrap wrap">
            <div class="header">免费课程</div>
            <div class="content simple">
                {{ show_courses(free_courses) }}
            </div>
        </div>
    {% endif %}

    {% if vip_courses|length > 0 %}
        <div class="index-wrap wrap">
            <div class="header">会员课程</div>
            <div class="content simple">
                {{ show_courses(vip_courses) }}
            </div>
        </div>
    {% endif %}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/index.js') }}

{% endblock %}