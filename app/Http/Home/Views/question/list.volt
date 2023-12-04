{% extends 'templates/main.volt' %}

{% block content %}

    {% if top_categories|length > 1 %}
        {{ partial('question/list_filter') }}
    {% endif %}

    {% set ask_url = url({'for':'home.question.add'}) %}
    {% set pager_url = url({'for':'home.question.pager'}, params) %}
    {% set hot_questions_url = url({'for':'home.widget.hot_questions'},{'limit':5}) %}
    {% set top_answerers_url = url({'for':'home.widget.top_answerers'},{'limit':5}) %}
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
                            <div id="question-list" data-url="{{ pager_url }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="sidebar wrap">
                <a class="layui-btn layui-btn-fluid btn-ask" data-url="{{ ask_url }}">我要提问</a>
            </div>
            <div class="sidebar" id="sidebar-hot-questions" data-url="{{ hot_questions_url }}"></div>
            <div class="sidebar" id="sidebar-top-answerers" data-url="{{ top_answerers_url }}"></div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/list.filter.js') }}
    {{ js_include('home/js/question.list.js') }}

{% endblock %}