{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

    {% set favorite_title = course.me.favorited ? '取消收藏' : '收藏' %}
    {% set favorite_star = course.me.favorited ? 'layui-icon-star-fill' : 'layui-icon-star' %}
    {% set full_course_url = full_url({'for':'web.course.show','id':course.id}) %}
    {% set favorite_url = url({'for':'web.course.favorite','id':course.id}) %}
    {% set qrcode_url = url({'for':'web.qrcode_img'},{'text':full_course_url}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ url({'for':'web.course.list'}) }}">全部课程</a>
            {% for path in course.category_paths %}
                <a href="{{ path.url }}">{{ path.name }}</a>
            {% endfor %}
            <a><cite>{{ course.title }}</cite></a>
        </span>
        <span class="share">
            <a href="javascript:" title="{{ favorite_title }}" data-url="{{ favorite_url }}"><i class="layui-icon {{ favorite_star }} icon-star"></i></a>
            <a href="javascript:" title="分享到微信" data-url="{{ qrcode_url }}"><i class="layui-icon layui-icon-login-wechat icon-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq icon-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo icon-weibo"></i></a>
        </span>
    </div>

    {{ partial('course/show_meta') }}

    <div class="layout-main clearfix">

        {% set show_tab_chapters = course.lesson_count > 0 %}
        {% set show_tab_packages = course.package_count > 0 %}
        {% set show_tab_consults = course.consult_count > 0 %}
        {% set show_tab_reviews = course.review_count > 0 %}

        <div class="layout-content">
            <div class="course-tab-wrap wrap">
                <div class="layui-tab layui-tab-brief course-tab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">详情</li>
                        {% if show_tab_chapters %}
                            <li>目录<span class="tab-count">{{ course.lesson_count }}</span></li>
                        {% endif %}
                        {% if show_tab_packages %}
                            <li>套餐<span class="tab-count">{{ course.package_count }}</span></li>
                        {% endif %}
                        {% if show_tab_consults %}
                            <li>咨询<span class="tab-count">{{ course.consult_count }}</span></li>
                        {% endif %}
                        {% if show_tab_reviews %}
                            <li>评价<span class="tab-count">{{ course.review_count }}</span></li>
                        {% endif %}
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="course-details">{{ course.details }}</div>
                        </div>
                        {% if show_tab_chapters %}
                            {% set chapters_url = url({'for':'web.course.chapters','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-chapters" data-url="{{ chapters_url }}"></div>
                        {% endif %}
                        {% if show_tab_packages %}
                            {% set packages_url = url({'for':'web.course.packages','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-packages" data-url="{{ packages_url }}"></div>
                        {% endif %}
                        {% if show_tab_consults %}
                            {% set consults_url = url({'for':'web.course.consults','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-consults" data-url="{{ consults_url }}"></div>
                        {% endif %}
                        {% if show_tab_reviews %}
                            {% set reviews_url = url({'for':'web.course.reviews','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-reviews" data-url="{{ reviews_url }}"></div>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>

        {% set show_sidebar_teachers = 1 %}
        {% set show_sidebar_topics = 1 %}
        {% set show_sidebar_recommended = 1 %}
        {% set show_sidebar_related = 1 %}

        <div class="layout-sidebar">
            <div class="sidebar">
                {{ partial('course/show_order') }}
            </div>
            <div class="sidebar">
                {{ partial('course/show_teacher') }}
            </div>
            {% if show_sidebar_topics %}
                {% set topics_url = url({'for':'web.course.topics','id':course.id}) %}
                <div class="sidebar" id="sidebar-topics" data-url="{{ topics_url }}"></div>
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

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ course.title }}">
        <input type="hidden" name="share.pic" value="{{ course.cover }}">
        <input type="hidden" name="share.url" value="{{ full_course_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/course.show.js') }}
    {{ js_include('web/js/course.share.js') }}

{% endblock %}