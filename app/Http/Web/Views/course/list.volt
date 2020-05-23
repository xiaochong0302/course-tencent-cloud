{% extends 'templates/base.volt' %}

{% block content %}

    {{ partial('course/list_filter') }}

    <div class="course-list clearfix">
        {{ partial('partials/macro_course') }}
        {% for item in pager.items %}
            {{ course_card(item) }}
        {% endfor %}
    </div>

    {{ partial('partials/pager') }}

{% endblock %}