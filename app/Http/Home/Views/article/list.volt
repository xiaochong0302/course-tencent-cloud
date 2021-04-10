{% extends 'templates/main.volt' %}

{% block content %}

    {% set category_val = request.get('category_id','int','all') %}
    {% set sort_val = request.get('sort','trim','latest') %}
    {% set pager_url = url({'for':'home.article.pager'}, params) %}
    {% set hot_author_url = url({'for':'home.article.hot_authors'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>专栏</cite></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="content-wrap wrap">
                <div class="article-sort">
                    {% for sort in sorts %}
                        {% set class = sort_val == sort.id ? 'layui-btn layui-btn-xs' : 'none' %}
                        <a class="{{ class }}" href="{{ sort.url }}">{{ sort.name }}</a>
                    {% endfor %}
                </div>
                <div class="article-list" id="article-list" data-url="{{ pager_url }}"></div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="sidebar">
                <div class="layui-card">
                    <div class="layui-card-header">文章分类</div>
                    <div class="layui-card-body">
                        <ul class="article-cate-list">
                            {% for category in categories %}
                                {% set class = category_val == category.id ? 'active' : '' %}
                                <li class="{{ class }}"><a href="{{ category.url }}">{{ category.name }}</a></li>
                            {% endfor %}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sidebar" id="hot-author-list" data-url="{{ hot_author_url }}"></div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/article.list.js') }}

{% endblock %}