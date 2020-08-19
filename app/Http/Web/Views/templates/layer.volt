<!DOCTYPE html>
<html lang="zh-CN-Hans">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrfToken.getToken() }}">
    <title>即时通讯</title>
    {{ icon_link('favicon.ico') }}
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('web/css/common.css') }}
    {% block link_css %}{% endblock %}
    {% block inline_css %}{% endblock %}
</head>
<body class="layer">
{% block content %}{% endblock %}

{{ partial('partials/js_global_vars') }}
{{ js_include('lib/layui/layui.js') }}
{{ js_include('web/js/common.js') }}

{% block include_js %}{% endblock %}
{% block inline_js %}{% endblock %}
</body>
</html>