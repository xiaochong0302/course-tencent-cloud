{% extends 'templates/full.volt' %}

{% block content %}

    {% set chapter_full_url = full_url({'for':'web.chapter.show','id':chapter.id}) %}
    {% set learning_url = url({'for':'web.chapter.learning','id':chapter.id}) %}
    {% set live_chats_url = url({'for':'web.live.chats','id':chapter.id}) %}
    {% set live_stats_url = url({'for':'web.live.stats','id':chapter.id}) %}
    {% set send_msg_url = url({'for':'web.live.send_msg','id':chapter.id}) %}
    {% set bind_user_url = url({'for':'web.live.bind_user','id':chapter.id}) %}
    {% set like_url = url({'for':'web.chapter.like','id':chapter.id}) %}
    {% set qrcode_url = url({'for':'web.qrcode_img'},{'text':chapter_full_url}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
        <span class="share">
            <a href="javascript:" title="点赞" data-url="{{ like_url }}"><i class="layui-icon layui-icon-praise icon-praise"></i><em class="like-count">{{ chapter.like_count }}</em></a>
            <a href="javascript:" title="在线人数"><i class="layui-icon layui-icon-user"></i><em>15</em></a>
            <a href="javascript:" title="分享到微信" data-url=""><i class="layui-icon layui-icon-login-wechat icon-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq icon-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo icon-weibo"></i></a>
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
                        {% if auth_user.id > 0 %}
                            <input class="layui-input" type="text" name="content" maxlength="50" placeholder="快来一起互动吧" lay-verType="tips" lay-verify="required">
                        {% else %}
                            <input class="layui-input" type="text" placeholder="登录后才可以发言哦" readonly="readonly">
                        {% endif %}
                        <button class="layui-hide" type="submit" lay-submit="true" lay-filter="chat">发送</button>
                    </form>
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

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ chapter.course.title }}">
        <input type="hidden" name="share.pic" value="{{ chapter.course.cover }}">
        <input type="hidden" name="share.url" value="{{ chapter_full_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block include_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>

    {{ js_include('web/js/chapter.live.player.js') }}
    {{ js_include('web/js/chapter.live.im.js') }}
    {{ js_include('web/js/course.share.js') }}

{% endblock %}