{% extends 'templates/main.volt' %}

{% block content %}

    {% set share_url = share_url('chapter',chapter.id,auth_user.id) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}
    {% set course_url = url({'for':'home.course.show','id':chapter.course.id}) %}
    {% set learning_url = url({'for':'home.chapter.learning','id':chapter.id}) %}
    {% set live_chats_url = url({'for':'home.live.chats','id':chapter.id}) %}
    {% set live_stats_url = url({'for':'home.live.stats','id':chapter.id}) %}
    {% set send_msg_url = url({'for':'home.live.send_msg','id':chapter.id}) %}
    {% set bind_user_url = url({'for':'home.live.bind_user','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="{{ course_url }}"><i class="layui-icon layui-icon-return"></i> 返回课程</a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
        <span class="share">
            <a href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat share-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq share-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo share-weibo"></i></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="player-wrap wrap">
                <div id="player"></div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="layui-card chat-wrap">
                <div class="layui-card-header">直播讨论</div>
                <div class="layui-card-body">
                    <div class="chat-msg-list" id="chat-msg-list" data-url="{{ live_chats_url }}"></div>
                    <form class="layui-form chat-msg-form" method="post" action="{{ send_msg_url }}">
                        <input class="layui-input" type="text" name="content" maxlength="50" placeholder="快来一起互动吧" lay-vertype="tips" lay-verify="required">
                        <button class="layui-hide" type="submit" lay-submit="true" lay-filter="chat">发送</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="layout-sticky">
        {{ partial('chapter/live/sticky') }}
    </div>

    <div class="layui-hide">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
        <input type="hidden" name="chapter.play_urls" value='{{ chapter.play_urls|json_encode }}'>
        <input type="hidden" name="chapter.me.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="live_stats_url" value='{{ live_stats_url }}'>
        <input type="hidden" name="bind_user_url" value='{{ bind_user_url }}'>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ chapter.course.title }}">
        <input type="hidden" name="share.pic" value="{{ chapter.course.cover }}">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('lib/dplayer/flv.min.js') }}
    {{ js_include('lib/dplayer/DPlayer.min.js') }}
    {{ js_include('home/js/chapter.live.player.js') }}
    {{ js_include('home/js/chapter.live.chat.js') }}
    {{ js_include('home/js/chapter.show.js') }}
    {{ js_include('home/js/course.share.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}
