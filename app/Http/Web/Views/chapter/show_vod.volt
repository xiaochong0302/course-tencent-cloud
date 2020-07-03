{% extends 'templates/full.volt' %}

{% block content %}

    {% set course_url = url({'for':'web.course.show','id':chapter.course.id}) %}
    {% set learning_url = url({'for':'web.chapter.learning','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <span><i class="layui-icon layui-icon-return"></i> <a href="{{ course_url }}">返回课程主页</a></span>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div id="player" class="container"></div>
            <div class="comment-list container"></div>
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/menu') }}
        </div>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="user.id" value="{{ auth_user.id }}">
        <input type="hidden" name="user.name" value="{{ auth_user.name }}">
        <input type="hidden" name="user.avatar" value="{{ auth_user.avatar }}">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
        <input type="hidden" name="chapter.play_urls" value='{{ chapter.play_urls|json_encode }}'>
    </div>

{% endblock %}

{% block include_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>

    {{ js_include('web/js/vod.player.js') }}

{% endblock %}