<!DOCTYPE html>
<html lang="zh-CN-Hans">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="{{ site_seo.getKeywords() }}">
    <meta name="description" content="{{ site_seo.getDescription() }}">
    <meta name="csrf-token" content="{{ csrfToken.getToken() }}">
    <title>{{ site_seo.getTitle() }}</title>
    {{ icon_link('favicon.ico') }}
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('web/css/common.css') }}
    {% block link_css %}{% endblock %}
    {% block inline_css %}{% endblock %}
</head>
<body>
<div id="header">
    {{ partial('partials/header') }}
</div>
<div id="main" class="layui-main">
    {% block content %}{% endblock %}
</div>
<div id="footer">
    {{ partial('partials/footer') }}
</div>
{{ js_include('lib/layui/layui.all.js') }}
{{ js_include('web/js/common.js') }}
{% block include_js %}{% endblock %}
{% block inline_js %}{% endblock %}
</body>
</html>