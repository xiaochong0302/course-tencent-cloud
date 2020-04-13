{% extends "templates/base.volt" %}

{% block content %}
    <img src="/qr/img?text=http://ctc.koogua.com">
{% endblock %}

{% block inline_css %}
    <style>
        .ok {
            font-weight: bold;
        }
    </style>
{% endblock %}

{% block inline_js %}
    <script>
        function ok() {
            console.log("fuck");
        }
    </script>
{% endblock %}