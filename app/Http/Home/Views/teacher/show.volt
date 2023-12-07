{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    {% set share_url = share_url('user',user.id,auth_user.id) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}
    {% set avatar_class = user.vip == 1 ? 'avatar vip' : 'avatar' %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>讲师</cite></a>
            <a><cite>{{ user.name }}</cite></a>
        </span>
        <span class="share">
            <a class="share-wechat" href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat"></i></a>
            <a class="share-qq" href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq"></i></a>
            <a class="share-weibo" href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo"></i></a>
            <a class="share-link kg-copy" href="javascript:" title="复制链接" data-clipboard-text="{{ share_url }}"><i class="layui-icon layui-icon-share"></i></a>
        </span>
    </div>

    <div class="user-profile wrap">
        <div class="{{ avatar_class }}">
            <img src="{{ user.avatar }}!avatar_160" alt="{{ user.name }}">
        </div>
        <div class="info">
            <p>
                <span class="name">{{ user.name }}</span>
                <span>{{ gender_icon(user.gender) }}</span>
            </p>
            <p>
                <span><i class="layui-icon layui-icon-location"></i></span>
                <span>{{ user.area|default('火星') }}</span>
            </p>
            <p>
                <span><i class="layui-icon layui-icon-time"></i></span>
                <span title="{{ date('Y-m-d H:i:s',user.active_time) }}">{{ user.active_time|time_ago }}</span>
            </p>
        </div>
        <div class="about">{{ user.about|default('这个家伙很懒，什么都没留下') }}</div>
    </div>

    {% set courses_url = url({'for':'home.teacher.courses','id':user.id}) %}

    <div class="tab-wrap">
        <div class="layui-tab layui-tab-brief user-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">点播课程</li>
                <li>直播课程</li>
                <li>图文课程</li>
                <li>面授课程</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show" id="tab-vod" data-url="{{ courses_url }}?model=1"></div>
                <div class="layui-tab-item" id="tab-live" data-url="{{ courses_url }}?model=2"></div>
                <div class="layui-tab-item" id="tab-read" data-url="{{ courses_url }}?model=3"></div>
                <div class="layui-tab-item" id="tab-offline" data-url="{{ courses_url }}?model=4"></div>
            </div>
        </div>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ user.name }}">
        <input type="hidden" name="share.pic" value="{{ user.avatar }}">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/teacher.show.js') }}
    {{ js_include('home/js/user.share.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}