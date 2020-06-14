{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layout-main clearfix">
        <div class="layout-content"></div>
        <div class="layout-sidebar">
            {{ partial('chapter/menu') }}
        </div>
    </div>

{% endblock %}