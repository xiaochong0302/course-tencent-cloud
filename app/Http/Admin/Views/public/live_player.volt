<!DOCTYPE html>
<html lang="zh-Hans-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>视频直播</title>
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

    var playUrls = JSON.parse('{{ pull_urls|json_encode }}');

    var options = {
        live: true,
        autoplay: true,
        h5_flv: true,
        width: 720,
        height: 405
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

    var player = new TcPlayer('player', options);

</script>
