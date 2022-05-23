<!DOCTYPE html>
<html lang="zh-CN-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrfToken.getToken() }}">
    <title>{{ site_info.title }}</title>
    {% if site_info.favicon %}
        {{ icon_link(site_info.favicon,false) }}
    {% else %}
        {{ icon_link('favicon.ico') }}
    {% endif %}
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('home/css/common.css') }}
    {% block link_css %}{% endblock %}
    {% block inline_css %}{% endblock %}
</head>
<body class="layer">
{% block content %}{% endblock %}

{{ partial('partials/js_vars') }}
{{ js_include('lib/layui/layui.js') }}
{{ js_include('home/js/common.js') }}

{% block include_js %}{% endblock %}
{% block inline_js %}{% endblock %}
</body>
</html>