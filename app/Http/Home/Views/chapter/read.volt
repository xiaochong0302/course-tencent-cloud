{% extends 'templates/main.volt' %}

{% block content %}

    {% set share_url = full_url('chapter',chapter.id,auth_user.id) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}
    {% set course_url = url({'for':'home.course.show','id':chapter.course.id}) %}
    {% set learning_url = url({'for':'home.chapter.learning','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
        </span>
        <span class="share">
            <a href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat share-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq share-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo share-weibo"></i></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="article-info wrap">
                <div class="title">{{ chapter.title }}</div>
                <div class="content ke-content">
                    {{ chapter.content }}
                </div>
            </div>
            <div id="comment-anchor"></div>
            <div class="article-comment wrap">
                {{ partial('chapter/comment') }}
            </div>
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/catalog') }}
        </div>
    </div>

    <div class="layout-sticky">
        {{ partial('chapter/sticky') }}
    </div>

    <div class="layui-hide">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
    </div>

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ chapter.course.title }}">
        <input type="hidden" name="share.pic" value="{{ chapter.course.cover }}">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/course.share.js') }}
    {{ js_include('home/js/chapter.read.js') }}
    {{ js_include('home/js/chapter.show.js') }}
    {{ js_include('home/js/comment.js') }}

{% endblock %}