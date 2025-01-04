{% extends 'templates/main.volt' %}

{% block content %}

    {% set pager_url = url({'for':'home.point_gift.pager'}) %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>积分商城</cite></a>
    </div>

    <div id="gift-list" data-url="{{ pager_url }}"></div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/point.gift.list.js') }}

{% endblock %}
