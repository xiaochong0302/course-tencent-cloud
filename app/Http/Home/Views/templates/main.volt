<!DOCTYPE html>
<html lang="zh-CN-Hans">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="renderer" content="webkit">
    <meta name="keywords" content="{{ seo.getKeywords() }}">
    <meta name="description" content="{{ seo.getDescription() }}">
    <meta name="csrf-token" content="{{ csrfToken.getToken() }}">
    <title>{{ seo.getTitle() }}</title>
    {% if site_info.favicon %}
        {{ icon_link(site_info.favicon,false) }}
    {% else %}
        {{ icon_link('favicon.ico') }}
    {% endif %}
    <link rel="preload" href="//at.alicdn.com/t/font_2760791_mj6x0o9n15s.woff2" as="font" type="font/woff2" crossorigin="anonymous">
    <link rel="stylesheet" href="//at.alicdn.com/t/font_2760791_mj6x0o9n15s.css">
    {{ css_link('lib/layui/css/layui.css') }}
    {{ css_link('home/css/common.css') }}
    {% block link_css %}{% endblock %}
    {% block inline_css %}{% endblock %}
</head>
<body class="main">
<div id="header">
    {{ partial('partials/header') }}
</div>
<div id="main" class="layui-main">
    {% block content %}{% endblock %}
</div>
<div id="footer">
    {{ partial('partials/footer') }}
</div>

{{ partial('partials/js_vars') }}
{{ js_include('lib/layui/layui.js') }}
{{ js_include('home/js/common.js') }}
{{ js_include('home/js/fixbar.js') }}

{% block include_js %}{% endblock %}
{% block inline_js %}{% endblock %}

{% if site_info.analytics_enabled == 1 %}
    <div class="layui-hide">
        {{ site_info.analytics_script }}
    </div>
{% endif %}

</body>
</html>