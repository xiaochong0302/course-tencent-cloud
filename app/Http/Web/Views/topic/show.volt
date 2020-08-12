{% extends 'templates/main.volt' %}

{% block content %}

    {% set courses_url = url({'for':'web.topic.courses','id':topic.id}) %}

    <div class="topic-info">
        <div class="topic-title">{{ topic.title }}</div>
    </div>

    <div id="course-list" data-url="{{ courses_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/topic.show.js') }}

{% endblock %}