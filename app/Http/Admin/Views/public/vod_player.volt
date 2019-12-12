<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>视频点播</title>
    <script src="//imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
<div id="player"></div>
</body>
</html>

{{ javascript_include('lib/jquery.min.js') }}

<script>

    var interval = null;
    var timeout = 5000;
    var requestId = uniqueId();
    var courseId = '{{ course_id }}';
    var chapterId = '{{ chapter_id }}';
    var playUrl = '{{ play_url }}';
    var playPosition = 0;

    var player = new TcPlayer('player', {
        m3u8: playUrl,
        autoplay: true,
        width: 720,
        height: 405,
        listener: function (msg) {
            if (msg.type == 'play') {
                start();
            } else if (msg.type == 'pause') {
                stop();
            } else if (msg.type == 'end') {
                stop();
            }
        }
    });

    if (playPosition > 0) {
        player.currentTime(playPosition);
    }

    function start() {
        if (interval != null) {
            clearInterval(interval);
            interval = null;
        }
        interval = setInterval(learning, timeout);
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
                course_id: courseId,
                chapter_id: chapterId,
                timeout: timeout,
                position: player.currentTime(),
            }
        });
    }

    function uniqueId(){
        var id = Date.now().toString(36);
        id += Math.random().toString(36).substr(3);
        return id;
    }

</script>