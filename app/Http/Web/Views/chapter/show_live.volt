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
        </div>
        <div class="layout-sidebar">
            <div class="sidebar-online container">
                <div class="layui-tab layui-tab-brief">
                    <ul class="layui-tab-title">
                        <li class="layui-this">讨论</li>
                        <li>成员</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show" id="tab-comments" data-url="#"></div>
                        <div class="layui-tab-item" id="tab-users" data-url="#"></div>
                    </div>
                </div>
            </div>
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
            live: true,
            autoplay: true,
            h5_flv: true,
            width: 760,
            height: 450
        };

        if (playUrls.rtmp && playUrls.rtmp.od) {
            options.rtmp = playUrls.rtmp.od;
        }

        if (playUrls.rtmp && playUrls.rtmp.hd) {
            options.rtmp_hd = playUrls.rtmp.hd;
        }

        if (playUrls.rtmp && playUrls.rtmp.sd) {
            options.rtmp_sd = playUrls.rtmp.sd;
        }

        if (playUrls.flv && playUrls.flv.od) {
            options.flv = playUrls.flv.od;
        }

        if (playUrls.flv && playUrls.flv.hd) {
            options.flv_hd = playUrls.flv.hd;
        }

        if (playUrls.flv && playUrls.flv.sd) {
            options.flv_sd = playUrls.flv.sd;
        }

        if (playUrls.m3u8 && playUrls.m3u8.od) {
            options.m3u8 = playUrls.m3u8.od;
        }

        if (playUrls.m3u8 && playUrls.m3u8.hd) {
            options.m3u8_hd = playUrls.m3u8.hd;
        }

        if (playUrls.m3u8 && playUrls.m3u8.sd) {
            options.m3u8_sd = playUrls.m3u8.sd;
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