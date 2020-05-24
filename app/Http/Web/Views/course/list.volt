{% extends 'templates/base.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

    {{ partial('course/list_filter') }}

    <div class="course-list clearfix">
        {% for item in pager.items %}
            {{ course_card(item) }}
        {% endfor %}
    </div>

    {{ partial('partials/pager') }}

{% endblock %}