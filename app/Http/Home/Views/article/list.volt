{% extends 'templates/main.volt' %}

{% block content %}

    {% if top_categories|length > 1 %}
        {{ partial('article/list_filter') }}
    {% endif %}

    {% set post_url = url({'for':'home.article.add'}) %}
    {% set pager_url = url({'for':'home.article.pager'}, params) %}
    {% set top_authors_url = url({'for':'home.widget.top_authors'},{'limit':5}) %}
    {% set sort_val = request.get('sort','trim','latest') %}

    <div class="layout-main">
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
            <div class="sidebar wrap">
                <a class="layui-btn layui-btn-fluid btn-post" data-url="{{ post_url }}">发布文章</a>
            </div>
            <div class="sidebar" id="sidebar-top-authors" data-url="{{ top_authors_url }}"></div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/list.filter.js') }}
    {{ js_include('home/js/article.list.js') }}

{% endblock %}