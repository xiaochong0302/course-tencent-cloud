{% extends 'templates/full.volt' %}

{% block content %}

    {% set course_url = url({'for':'web.course.show','id':chapter.course.id}) %}

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

{% endblock %}

{% block include_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>

{% endblock %}

{% block inline_js %}

    <script>

        var interval = null;
        var intervalTime = 5000;
        var position = 0;
        var chapterId = '{{ chapter.id }}';
        var courseId = '{{ chapter.course.id }}';
        var planId = '{{ chapter.me.plan_id }}';
        var userId = '{{ auth_user.id }}';
        var requestId = getRequestId();
        var playUrls = JSON.parse('{{ chapter.play_urls|json_encode }}');

        var options = {
            autoplay: false,
            width: 760,
            height: 450
        };

        if (playUrls.od) {
            options.m3u8 = playUrls.od.url;
        }

        if (playUrls.hd) {
            options.m3u8_hd = playUrls.hd.url;
        }

        if (playUrls.sd) {
            options.m3u8_sd = playUrls.sd.url;
        }

        if (userId !== '0' && planId !== '0') {
            options.listener = function (msg) {
                if (msg.type === 'play') {
                    start();
                } else if (msg.type === 'pause') {
                    stop();
                } else if (msg.type === 'end') {
                    stop();
                }
            }
        }

        var player = new TcPlayer('player', options);

        if (position > 0) {
            player.currentTime(position);
        }

        function start() {
            if (interval != null) {
                clearInterval(interval);
                interval = null;
            }
            interval = setInterval(learning, intervalTime);
        }

        function stop() {
            clearInterval(interval);
            interval = null;
        }

        function learning() {
            $.ajax({
                type: 'POST',
                url: '/learning',
                data: {
                    request_id: requestId,
                    chapter_id: chapterId,
                    course_id: courseId,
                    user_id: userId,
                    plan_id: planId,
                    interval: intervalTime,
                    position: player.currentTime(),
                }
            });
        }

        function getRequestId() {
            var id = Date.now().toString(36);
            id += Math.random().toString(36).substr(3);
            return id;
        }

    </script>

{% endblock %}