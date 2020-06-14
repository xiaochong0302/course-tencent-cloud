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

{% block inline_js %}

    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>

    <script>

        var interval = null;
        var intervalTime = 5000;
        var requestId = getRequestId();
        var chapterId = '{{ chapter.id }}';
        var playUrl = 'https://1255691183.vod2.myqcloud.com/81258db0vodtransgzp1255691183/89b3d8955285890796532522693/v.f220.m3u8?t=5ee5c6ed&exper=0&us=697028&sign=2b7fd89eff92236184eadbaa14a895dd';
        var position = 0;

        var player = new TcPlayer('player', {
            m3u8: playUrl,
            autoplay: true,
            width: 760,
            height: 450,
            listener: function (msg) {
                if (msg.type === 'play') {
                    start();
                } else if (msg.type === 'pause') {
                    stop();
                } else if (msg.type === 'end') {
                    stop();
                }
            }
        });

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
                type: 'GET',
                url: '/admin/vod/learning',
                data: {
                    request_id: requestId,
                    chapter_id: chapterId,
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