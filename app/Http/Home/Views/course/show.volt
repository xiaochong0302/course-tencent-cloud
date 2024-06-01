{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {% set share_url = share_url('course',course.id,auth_user.id) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ url({'for':'home.course.list'}) }}">全部课程</a>
            <a><cite>{{ course.title }}</cite></a>
        </span>
        <span class="share">
            <a class="share-wechat" href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat"></i></a>
            <a class="share-qq" href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq"></i></a>
            <a class="share-weibo" href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo"></i></a>
            <a class="share-link kg-copy" href="javascript:" title="复制链接" data-clipboard-text="{{ share_url }}"><i class="layui-icon layui-icon-share"></i></a>
        </span>
    </div>

    {{ partial('course/show_meta') }}

    <div class="layout-main">

        {% set show_tab_chapters = course.lesson_count > 0 %}
        {% set show_tab_packages = course.package_count > 0 %}
        {% set show_tab_consults = course.consult_count > 0 %}
        {% set show_tab_reviews = course.review_count > 0 %}
        {% set show_tab_resources = course.resource_count > 0 %}

        <div class="layout-content">
            <div class="course-tab-wrap wrap">
                <div class="layui-tab layui-tab-brief course-tab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">详情</li>
                        <li>目录</li>
                        {% if show_tab_packages %}
                            <li>套餐<span class="tab-count">{{ course.package_count }}</span></li>
                        {% endif %}
                        {% if show_tab_consults %}
                            <li>咨询<span class="tab-count">{{ course.consult_count }}</span></li>
                        {% endif %}
                        {% if show_tab_reviews %}
                            <li>评价<span class="tab-count">{{ course.review_count }}</span></li>
                        {% endif %}
                        {% if show_tab_resources %}
                            <li>课件<span class="tab-count">{{ course.resource_count }}</span></li>
                        {% endif %}
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="course-details ke-content kg-zoom">{{ course.details }}</div>
                        </div>
                        <div class="layui-tab-item">
                            {{ partial('course/show_catalog') }}
                        </div>
                        {% if show_tab_packages %}
                            {% set packages_url = url({'for':'home.course.packages','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-packages" data-url="{{ packages_url }}"></div>
                        {% endif %}
                        {% if show_tab_consults %}
                            {% set consults_url = url({'for':'home.course.consults','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-consults" data-url="{{ consults_url }}"></div>
                        {% endif %}
                        {% if show_tab_reviews %}
                            {% set reviews_url = url({'for':'home.course.reviews','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-reviews" data-url="{{ reviews_url }}"></div>
                        {% endif %}
                        {% if show_tab_resources %}
                            {% set resources_url = url({'for':'home.course.resources','id':course.id}) %}
                            <div class="layui-tab-item" id="tab-resources" data-url="{{ resources_url }}"></div>
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>

        {% set show_sidebar_topics = 1 %}
        {% set show_sidebar_related = 1 %}

        <div class="layout-sidebar">
            {{ partial('course/show_order') }}
            {{ partial('course/show_teacher') }}
            {% if show_sidebar_topics %}
                {% set topics_url = url({'for':'home.course.topics','id':course.id}) %}
                <div class="sidebar" id="sidebar-topics" data-url="{{ topics_url }}"></div>
            {% endif %}
            {% if show_sidebar_related %}
                {% set related_url = url({'for':'home.course.related','id':course.id}) %}
                <div class="sidebar" id="sidebar-related" data-url="{{ related_url }}"></div>
            {% endif %}
        </div>

    </div>

    <div class="layout-sticky">
        {{ partial('course/sticky') }}
    </div>

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ course.title }}">
        <input type="hidden" name="share.pic" value="{{ course.cover }}">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/course.show.js') }}
    {{ js_include('home/js/course.share.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}