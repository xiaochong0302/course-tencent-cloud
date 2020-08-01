{% extends 'templates/main.volt' %}

{% block content %}

    {% set pager_url = url({'for':'web.im_group.pager'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>群组</cite></a>
    </div>

    <div id="group-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/im_group.list.js') }}

{% endblock %}