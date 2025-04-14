{% extends 'templates/main.volt' %}

{% block content %}

    {% set course_url = url({'for':'home.course.show','id':chapter.course.id}) %}
    {% set live_status_url = url({'for':'home.live.status','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    {% if time() < chapter.start_time %}
        <div class="live-preview countdown wrap">
            <div class="icon"><i class="layui-icon layui-icon-time"></i></div>
            <div class="timer"></div>
            <div class="tips">直播倒计时开始啦，敬请关注！</div>
        </div>
    {% elseif chapter.start_time < time() and chapter.end_time > time() %}
        <div class="live-preview countdown wrap">
            <div class="icon"><i class="layui-icon layui-icon-face-surprised"></i></div>
            <div class="timer"></div>
            <div class="tips">直播时间到了，老师去哪了？</div>
        </div>
    {% else %}
        <div class="live-preview wrap">
            <div class="icon"><i class="layui-icon layui-icon-tree"></i></div>
            <div class="tips">直播已结束，谢谢关注！</div>
        </div>
    {% endif %}

    <div class="layui-hide">
        <input type="hidden" name="live.status_url" value="{{ live_status_url }}">
        <input type="hidden" name="countdown.end_time" value="{{ chapter.start_time }}">
        <input type="hidden" name="countdown.server_time" value="{{ time() }}">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/chapter.live.countdown.js') }}

{% endblock %}
