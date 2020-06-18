{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a href="{{ url({'for':'web.course.list'}) }}">全部课程</a>
        <a href="{{ url({'for':'web.course.show','id':chapter.course.id}) }}">{{ chapter.course.title }}</a>
        <a><cite>{{ chapter.title }}</cite></a>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="live-player container">
                <div id="player"></div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div id="sidebar-live-stats" data-url="{{ url({'for':'web.live.stats','id':chapter.id}) }}"></div>
        </div>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="user.id" value="{{ auth_user.id }}">
        <input type="hidden" name="user.name" value="{{ auth_user.name }}">
        <input type="hidden" name="user.avatar" value="{{ auth_user.avatar }}">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="chapter.play_urls" value='{{ chapter.play_urls|json_encode }}'>
        <input type="hidden" name="chapter.learning_url" value="{{ url({'for':'web.learning','id':chapter.id}) }}">
        <input type="hidden" name="im.members_url" value="{{ url({'for':'web.live.members','id':chapter.id}) }}">
        <input type="hidden" name="im.bind_user_url" value="{{ url({'for':'web.live.bind','id':chapter.id}) }}">
        <input type="hidden" name="im.send_msg_url" value="{{ url({'for':'web.live.message','id':chapter.id}) }}">
    </div>

{% endblock %}

{% block include_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>

    {{ js_include('lib/layui/layui.js') }}
    {{ js_include('web/js/live.player.js') }}
    {{ js_include('web/js/live.im.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        refreshLiveStats();

        setInterval('refreshLiveStats()', 60000);

        function refreshLiveStats() {
            var $liveStats = $('#sidebar-live-stats');
            helper.ajaxLoadHtml($liveStats.attr('data-url'), $liveStats.attr('id'));
        }

    </script>

{% endblock %}