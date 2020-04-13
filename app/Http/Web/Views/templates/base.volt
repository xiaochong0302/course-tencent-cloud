<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="{{ seo.getKeywords() }}">
    <meta name="description" content="{{ seo.getDescription() }}">
    <title>{{ seo.getTitle() }}</title>
    {{ icon_link("favicon.ico") }}
    {{ css_link("lib/layui/css/layui.css") }}
    {{ css_link("web/css/style.css") }}
    {% block header_css %}{% endblock %}
    {% block inline_css %}{% endblock %}
</head>
<body>
{% block content %}{% endblock %}
{{ js_include("lib/layui/layui.js") }}
{% block footer_js %}{% endblock %}
{% block inline_js %}{% endblock %}
</body>
</html>