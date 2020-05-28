{% extends 'templates/base.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ url({'for':'web.course.list'}) }}">全部课程</a>
            {% for path in category_paths %}
                <a href="{{ path.url }}">{{ path.name }}</a>
            {% endfor %}
            <a><cite>{{ course.title }}</cite></a>
        </span>
    </div>

    <div class="course-meta module clearfix">
        {{ partial('course/meta') }}
    </div>

    {% set show_packages = packages ? 1 : 0 %}
    {% set show_consults = course.market_price > 0 ? 1 : 0 %}
    {% set show_reviews = course.market_price > 0 ? 1 : 0 %}

    <div class="layout-main clearfix">
        <div class="layout-content module">
            <div class="layui-tab layui-tab-brief course-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">详情</li>
                    <li>目录</li>
                    {% if show_packages == 1 %}
                        <li>套餐</li>
                    {% endif %}
                    {% if show_consults == 1 %}
                        <li>咨询</li>
                    {% endif %}
                    {% if show_reviews == 1 %}
                        <li>评价</li>
                    {% endif %}
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="course-details">{{ course.details }}</div>
                    </div>
                    <div class="layui-tab-item" id="tab-chapters">
                        {% if course.model == 'vod' %}
                            {{ partial('course/chapters_vod') }}
                        {% elseif course.model == 'live' %}
                            {{ partial('course/chapters_live') }}
                        {% elseif course.model == 'read' %}
                            {{ partial('course/chapters_read') }}
                        {% endif %}
                    </div>
                    {% if show_packages == 1 %}
                        <div class="layui-tab-item" id="tab-packages">
                            {{ partial('course/packages') }}
                        </div>
                    {% endif %}
                    {% if show_consults == 1 %}
                        {% set consult_url = url({'for':'web.course.consults','id':course.id}) %}
                        <div class="layui-tab-item" id="tab-consults" data-url="{{ consult_url }}"></div>
                    {% endif %}
                    {% if show_reviews == 1 %}
                        {% set review_url = url({'for':'web.course.reviews','id':course.id}) %}
                        <div class="layui-tab-item" id="tab-reviews" data-url="{{ review_url }}"></div>
                    {% endif %}
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            {% if teachers %}
                {{ partial('course/sidebar_teachers') }}
            {% endif %}
            {% if topics %}
                {{ partial('course/sidebar_topics') }}
            {% endif %}
            {% if recommended_courses %}
                {{ partial('course/sidebar_recommended') }}
            {% endif %}
            {% if related_courses %}
                {{ partial('course/sidebar_related') }}
            {% endif %}
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        if ($('#tab-consults').length > 0) {
            console.log('#tab-consults#');
            var obj = $('#tab-consults');
            helper.ajaxPager(obj.attr('data-url'), obj.attr('id'));
        }

        if ($('#tab-reviews').length > 0) {
            console.log('#tab-reviews#');
            var obj = $('#tab-reviews');
            helper.ajaxPager(obj.attr('data-url'), obj.attr('id'));
        }

    </script>

{% endblock %}