{% extends 'templates/full.volt' %}

{% block content %}

    {% set course_url = url({'for':'web.course.show','id':chapter.course.id}) %}
    {% set learning_url = url({'for':'web.chapter.learning','id':chapter.id}) %}
    {% set stats_url = url({'for':'web.live.stats','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <span><i class="layui-icon layui-icon-return"></i> <a href="{{ course_url }}">返回课程主页</a></span>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="live-player container">
                <div id="player"></div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="layui-tab layui-tab-brief user-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">讨论</li>
                    <li>成员</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">
                        <div class="live-msg-list"></div>
                        <div class="live-msg-form">
                            <form class="layui-form" method="post" action="{{ url({'for':'web.live.message'}) }}">
                                <input class="layui-input" type="text" name="content" placeholder="请输入内容..." lay-verify="required">
                                <button class="layui-hide" type="submit" lay-submit="true" lay-filter="chat">发送</button>
                            </form>
                        </div>
                    </div>
                    <div class="layui-tab-item" id="tab-stats" data-url="{{ stats_url }}"></div>
                </div>
            </div>
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

    {{ js_include('web/js/live.player.js') }}
    {{ js_include('web/js/live.im.js') }}

{% endblock %}