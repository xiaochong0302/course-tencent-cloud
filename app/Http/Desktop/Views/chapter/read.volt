{% extends 'templates/main.volt' %}

{% block content %}

    {% set chapter_full_url = full_url({'for':'desktop.chapter.show','id':chapter.id}) %}
    {% set course_url = url({'for':'desktop.course.show','id':chapter.course.id}) %}
    {% set learning_url = url({'for':'desktop.chapter.learning','id':chapter.id}) %}
    {% set like_url = url({'for':'desktop.chapter.like','id':chapter.id}) %}
    {% set consult_url = url({'for':'desktop.consult.add'},{'chapter_id':chapter.id}) %}
    {% set qrcode_url = url({'for':'desktop.qrcode'},{'text':chapter_full_url}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
        <span class="share">
            <a href="javascript:" title="点赞" data-url="{{ like_url }}"><i class="layui-icon layui-icon-praise icon-praise"></i><em class="like-count">{{ chapter.like_count }}</em></a>
            <a href="javascript:" title="学习人次"><i class="layui-icon layui-icon-user"></i><em>{{ chapter.user_count }}</em></a>
            <a href="javascript:" title="我要提问" data-url="{{ consult_url }}"><i class="layui-icon layui-icon-help icon-help"></i></a>
            <a href="javascript:" title="分享到微信" data-url=""><i class="layui-icon layui-icon-login-wechat icon-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq icon-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo icon-weibo"></i></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="read-info wrap" id="preview">{{ chapter.content }}</div>
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/catalog') }}
        </div>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
    </div>

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ chapter.course.title }}">
        <input type="hidden" name="share.pic" value="{{ chapter.course.cover }}">
        <input type="hidden" name="share.url" value="{{ chapter_full_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/method.min.js', false) }}
    {{ js_include('desktop/js/markdown.preview.js') }}
    {{ js_include('desktop/js/course.share.js') }}
    {{ js_include('desktop/js/chapter.read.js') }}
    {{ js_include('desktop/js/chapter.action.js') }}

{% endblock %}