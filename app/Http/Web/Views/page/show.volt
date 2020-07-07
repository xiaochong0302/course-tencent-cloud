{% extends 'templates/full.volt' %}

{% block content %}

    <h3 class="page-title">{{ page.title }}</h3>

    <div class="page-content container">{{ page.content }}</div>

{% endblock %}