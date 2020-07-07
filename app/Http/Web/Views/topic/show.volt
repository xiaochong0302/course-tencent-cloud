{% extends 'templates/full.volt' %}

{% block content %}

    {% set courses_url = url({'for':'web.topic.courses','id':topic.id}) %}

    <div class="topic-info container">
        <h3 class="title" title="{{ topic.summary|e }}">{{ topic.title }}</h3>
    </div>

    <div id="course-list" data-url="{{ courses_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/topic.show.js') }}

{% endblock %}