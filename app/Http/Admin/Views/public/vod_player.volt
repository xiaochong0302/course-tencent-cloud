<!DOCTYPE html>
<html lang="zh-Hans-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>视频点播</title>
    <script src="https://imgcache.qq.com/open/qcloud/video/vcplayer/TcPlayer-2.3.2.js"></script>
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

<script>

    var playUrl = '{{ play_url }}';

    var player = new TcPlayer('player', {
        m3u8: playUrl,
        autoplay: false,
        width: 720,
        height: 405
    });

</script>