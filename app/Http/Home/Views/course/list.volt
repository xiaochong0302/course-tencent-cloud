{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('course/list_filter') }}

    {% set pager_url = url({'for':'home.course.pager'}, params) %}

    <div id="course-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/course.list.js') }}

{% endblock %}