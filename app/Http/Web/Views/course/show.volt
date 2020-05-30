{% extends 'templates/base.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ url({'for':'web.course.list'}) }}">全部课程</a>
            {% for path in course.category_paths %}
                <a href="{{ path.url }}">{{ path.name }}</a>
            {% endfor %}
            <a><cite>{{ course.title }}</cite></a>
        </span>
    </div>

    <div class="course-meta module clearfix">
        {{ partial('course/meta') }}
    </div>

    <div class="layout-main clearfix">

        {% set show_tab_packages = 1 %}
        {% set show_tab_consults = course.consult_count > 0 ? 1 : 0 %}
        {% set show_tab_reviews = course.review_count > 0 ? 1 : 0 %}

        <div class="layout-content module">
            <div class="layui-tab layui-tab-brief course-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">详情</li>
                    <li>目录</li>
                    {% if show_tab_packages == 1 %}
                        <li>套餐</li>
                    {% endif %}
                    {% if show_tab_consults == 1 %}
                        <li>咨询</li>
                    {% endif %}
                    {% if show_tab_reviews == 1 %}
                        <li>评价</li>
                    {% endif %}
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="course-details">{{ course.details }}</div>
                    </div>
                    <div class="layui-tab-item">
                        {{ partial('course/chapters') }}
                    </div>
                    {% if show_tab_packages == 1 %}
                        {% set package_url = url({'for':'web.course.packages','id':course.id}) %}
                        <div class="layui-tab-item" id="tab-packages" data-url="{{ package_url }}"></div>
                    {% endif %}
                    {% if show_tab_consults == 1 %}
                        {% set consult_url = url({'for':'web.course.consults','id':course.id}) %}
                        <div class="layui-tab-item" id="tab-consults" data-url="{{ consult_url }}"></div>
                    {% endif %}
                    {% if show_tab_reviews == 1 %}
                        {% set review_url = url({'for':'web.course.reviews','id':course.id}) %}
                        <div class="layui-tab-item" id="tab-reviews" data-url="{{ review_url }}"></div>
                    {% endif %}
                </div>
            </div>
        </div>

        {% set show_sidebar_teachers = 1 %}
        {% set show_sidebar_topics = 1 %}
        {% set show_sidebar_recommended = 1 %}
        {% set show_sidebar_related = 1 %}

        <div class="layout-sidebar">
            {{ partial('course/order') }}
            {{ partial('course/teachers') }}
            {% if show_sidebar_topics %}
                {% set topic_url = url({'for':'web.course.topics','id':course.id}) %}
                <div class="sidebar" id="sidebar-topics" data-url="{{ topic_url }}"></div>
            {% endif %}
            {% if show_sidebar_recommended %}
                {% set recommended_url = url({'for':'web.course.recommended','id':course.id}) %}
                <div class="sidebar" id="sidebar-recommended" data-url="{{ recommended_url }}"></div>
            {% endif %}
            {% if show_sidebar_related %}
                {% set related_url = url({'for':'web.course.related','id':course.id}) %}
                <div class="sidebar" id="sidebar-related" data-url="{{ related_url }}"></div>
            {% endif %}
        </div>

    </div>

{% endblock %}

{% block inline_js %}

    <script>
        if ($('#tab-packages').length > 0) {
            var $tabPackages = $('#tab-packages');
            helper.ajaxLoadHtml($tabPackages.attr('data-url'), $tabPackages.attr('id'));
        }
        if ($('#tab-consults').length > 0) {
            var $tabConsults = $('#tab-consults');
            helper.ajaxLoadHtml($tabConsults.attr('data-url'), $tabConsults.attr('id'));
        }
        if ($('#tab-reviews').length > 0) {
            var $tabReviews = $('#tab-reviews');
            helper.ajaxLoadHtml($tabReviews.attr('data-url'), $tabReviews.attr('id'));
        }
        if ($('#sidebar-topics').length > 0) {
            var $sdTopics = $('#sidebar-topics');
            helper.ajaxLoadHtml($sdTopics.attr('data-url'), $sdTopics.attr('id'));
        }
        if ($('#sidebar-recommended').length > 0) {
            var $sdRecommended = $('#sidebar-recommended');
            helper.ajaxLoadHtml($sdRecommended.attr('data-url'), $sdRecommended.attr('id'));
        }
        if ($('#sidebar-related').length > 0) {
            var $sdRelated = $('#sidebar-related');
            helper.ajaxLoadHtml($sdRelated.attr('data-url'), $sdRelated.attr('id'));
        }
    </script>

{% endblock %}