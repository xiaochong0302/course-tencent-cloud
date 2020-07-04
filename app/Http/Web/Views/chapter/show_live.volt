{% extends 'templates/full.volt' %}

{% block content %}

    {% set learning_url = url({'for':'web.chapter.learning','id':chapter.id}) %}
    {% set live_chats_url = url({'for':'web.live.chats','id':chapter.id}) %}
    {% set live_stats_url = url({'for':'web.live.stats','id':chapter.id}) %}
    {% set send_msg_url = url({'for':'web.live.send_msg','id':chapter.id}) %}
    {% set bind_user_url = url({'for':'web.live.bind_user','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="live-player container">
                <div id="player"></div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="chat-container">
                <div class="layui-tab layui-tab-brief user-tab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">讨论</li>
                        <li>统计</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <div class="chat-msg-list" id="chat-msg-list" data-url="{{ live_chats_url }}"></div>
                            <div class="chat-msg-form">
                                <form class="layui-form" method="post" action="{{ send_msg_url }}">
                                    <input class="layui-input" type="text" name="content" maxlength="150" placeholder="快来和大家一起互动吧~" lay-verType="tips" lay-verify="required">
                                    <button class="layui-hide" type="submit" lay-submit="true" lay-filter="chat">发送</button>
                                </form>
                            </div>
                        </div>
                        <div class="layui-tab-item" id="tab-stats" data-url="{{ live_stats_url }}"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
        <input type="hidden" name="chapter.play_urls" value='{{ chapter.play_urls|json_encode }}'>
        <input type="hidden" name="bind_user_url" value='{{ bind_user_url }}'>
    </div>

{% endblock %}

{% block include_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>

    {{ js_include('web/js/live.player.js') }}
    {{ js_include('web/js/live.im.js') }}

{% endblock %}