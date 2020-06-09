{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}
    {{ partial('course/list_filter') }}

    {% if pager.total_pages > 0 %}
        <div class="course-list clearfix">
            <div class="layui-row layui-col-space20">
                {% for item in pager.items %}
                    <div class="layui-col-md3">
                        {{ course_card(item) }}
                    </div>
                {% endfor %}
            </div>
        </div>
        {{ partial('partials/pager') }}
    {% else %}
        <div class="search-empty container">
            <div class="icon"><i class="layui-icon layui-icon-face-surprised"></i></div>
            <div class="text">没有检索到相关课程哦</div>
        </div>
    {% endif %}

{% endblock %}