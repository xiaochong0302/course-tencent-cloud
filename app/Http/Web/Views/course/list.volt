{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}
    {{ partial('course/list_filter') }}

    {% if pager.total_pages > 0 %}
        <div class="course-list container clearfix">
            {% for item in pager.items %}
                {{ course_card(item) }}
            {% endfor %}
        </div>
        {{ partial('partials/pager') }}
    {% else %}
        <div class="search-empty container">
            <div class="icon"><i class="layui-icon layui-icon-face-surprised"></i></div>
            <div class="text">没有检索到相关课程哦</div>
        </div>
    {% endif %}

{% endblock %}