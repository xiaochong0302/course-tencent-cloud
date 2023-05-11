{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {% set share_url = share_url('page',page.id,auth_user.id) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>{{ page.title }}</cite></a>
        </span>
        <span class="share">
            <a class="share-wechat" href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat"></i></a>
            <a class="share-qq" href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq"></i></a>
            <a class="share-weibo" href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo"></i></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="page-info wrap">
                <div class="content ke-content">{{ page.content }}</div>
            </div>
        </div>
        <div class="layout-sidebar">
            {% if featured_courses %}
                <div class="sidebar">
                    <div class="layui-card">
                        <div class="layui-card-header">推荐课程</div>
                        <div class="layui-card-body">
                            {% for course in featured_courses %}
                                {{ sidebar_course_card(course) }}
                            {% endfor %}
                        </div>
                    </div>
                </div>
            {% endif %}
        </div>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ page.title }}">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}