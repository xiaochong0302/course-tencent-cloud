{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('course/list_filter') }}

    <div id="course-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/course.list.js') }}

{% endblock %}