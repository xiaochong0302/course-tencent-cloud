{% extends 'templates/main.volt' %}

{% block content %}

    {% set course_url = url({'for':'desktop.course.show','id':chapter.course.id}) %}
    {% set live_status_url = url({'for':'desktop.live.status','id':chapter.id}) %}
    {% set show_countdown = time() < chapter.start_time ? 1 : 0 %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    {% if show_countdown == 1 %}
        <div class="countdown">
            <div class="icon"><i class="layui-icon layui-icon-time"></i></div>
            <div class="timer"></div>
            <div class="tips">开播倒计时开始啦，敬请关注！</div>
        </div>
    {% else %}
        <div class="countdown">
            <div class="icon"><i class="layui-icon layui-icon-face-surprised"></i></div>
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

    {{ js_include('desktop/js/chapter.live.countdown.js') }}

{% endblock %}