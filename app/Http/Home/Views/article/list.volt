{% extends 'templates/main.volt' %}

{% block content %}

    {% set sort_val = request.get('sort','trim','latest') %}
    {% set pager_url = url({'for':'home.article.pager'}, params) %}
    {% set hot_authors_url = url({'for':'home.article.hot_authors'}) %}
    {% set my_tags_url = url({'for':'home.widget.my_tags'},{'type':'article'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>专栏</cite></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="content-wrap wrap">
                <div class="layui-tab layui-tab-brief search-tab">
                    <ul class="layui-tab-title">
                        {% for sort in sorts %}
                            {% set class = sort_val == sort.id ? 'layui-this' : 'none' %}
                            <li class="{{ class }}"><a href="{{ sort.url }}">{{ sort.name }}</a></li>
                        {% endfor %}
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div id="article-list" data-url="{{ pager_url }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            {% if auth_user.id > 0 %}
                <div class="sidebar" id="sidebar-my-tags" data-url="{{ my_tags_url }}"></div>
            {% endif %}
            <div class="sidebar" id="sidebar-hot-authors" data-url="{{ hot_authors_url }}"></div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/article.list.js') }}

{% endblock %}